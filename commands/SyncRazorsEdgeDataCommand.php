<?php

namespace DMA\FriendsRE\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Rainlab\User\Models\User;
use DMA\Friends\Models\Usermeta;
use DMA\FriendsRE\Models\RazorsEdge;
use DMA\FriendsRE\Models\Settings;

class SyncRazorsEdgeDataCommand extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'friends:sync-razorsedge';

    /**
     * @var string The console command description.
     */
    protected $description = 'Syncronizes data in razors edge with friends users.';

    /** 
     * @var object Contains the database object when fired
     */
    protected $db = null;

    /** 
     * @var Number of records to process per run
     */
    protected $limit = 1000; 

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        if (!$this->limit = $this->option('limit')) {
            $this->limit = Settings::get('limit', $this->limit);
        }

        $this->db = DB::connection('razorsedge');
        $this->sync();
    }

    protected function sync()
    {

        $reData = $this->pageQuery();

        foreach($reData as $row) {
            if (empty($row->EMAIL)) continue;

            $user = User::where('email', $row->EMAIL)->first();

            if ($user) {
                $re                 = new RazorsEdge;
                $re->razorsedge_id  = $row->CONSTITUENT_ID;
                $re->member_id      = $row->MemberID;
                $re->expires_on     = date('Y-m-d H:i:s', strtotime($row->ExpiresOn));
                $re->first_name     = $row->FIRST_NAME;
                $re->last_name      = $row->LAST_NAME;
                $re->address        = $row->ADDRESS_BLOCK;
                $re->city           = $row->CITY;
                $re->state          = $row->STATE;
                $re->zip            = $row->POST_CODE;
                $re->member_level   = $row->Category;

                $user->metadata->current_member_number = $row->CONSTITUENT_ID;
                $user->metadata->current_member = Usermeta::IS_MEMBER;

                // 3 is used for companies and organizations
                if ($row->SEX != 3) {
                    $user->metadata->gender = Usermeta::$genderOptions[$row->SEX - 1];
                }

                // Remove any existing records
                Razorsedge::where('user_id', $user->id)->delete();
                
                // Save a new record
                $user->razorsedge()->save($re);
                $user->push();

                $this->output->writeln('saved razors edge data for: ' . $row->EMAIL);
            }
        }

        $this->output->writeln('sync complete');
    }

    protected function pageQuery() {
        if ($this->option('reset')) Settings::set('sync_range', 0);

        $range = Settings::get('sync_range', 0);

        $this->output->writeln('begin query with range ' . $range);
        $re = new RazorsEdge;
        $id = $range;
        $range += $this->limit;

        $query = "
            SELECT
            TOP " . $this->limit . "
                RECORDS.CONSTITUENT_ID, RECORDS.FIRST_NAME, RECORDS.LAST_NAME, RECORDS.FULL_NAME, RECORDS.SEX,
                ADDRESS.ADDRESS_BLOCK, ADDRESS.CITY, ADDRESS.STATE, ADDRESS.POST_CODE, ADDRESS.DATE_LAST_CHANGED,
                PHONES.NUM as EMAIL,
                MAX(Member.ID) as MemberID,
                MAX(MembershipTransaction.ExpiresOn) as ExpiresOn,
                MAX(MembershipTransaction.Category) as Category
            FROM
                RECORDS,
                CONSTIT_ADDRESS,
                CONSTIT_ADDRESS_PHONES,
                ADDRESS,
                PHONES,
                TABLEENTRIES AS TABLEENTRIES_phones,
                Member, MembershipTransaction
            WHERE
                RECORDS.ID = CONSTIT_ADDRESS.CONSTIT_ID AND
                CONSTIT_ADDRESS.ADDRESS_ID = ADDRESS.ID AND CONSTIT_ADDRESS.PREFERRED = '-1' AND
                CONSTIT_ADDRESS_PHONES.CONSTITADDRESSID = CONSTIT_ADDRESS.ID AND
                CONSTIT_ADDRESS_PHONES.PHONESID = PHONES.PHONESID AND
                PHONES.PHONETYPEID = TABLEENTRIES_phones.TABLEENTRIESID AND
                Member.ConstitID = RECORDS.ID AND
                MembershipTransaction.MembershipID = Member.ID 
 
            and TABLEENTRIES_phones.LONGDESCRIPTION  = 'E-mail'
            and RECORDS.ID > " . $id . "
            group by RECORDS.ID, RECORDS.CONSTITUENT_ID, RECORDS.FIRST_NAME, RECORDS.LAST_NAME, RECORDS.FULL_NAME, RECORDS.SEX,                                                   
                  ADDRESS.ADDRESS_BLOCK, ADDRESS.CITY, ADDRESS.STATE, ADDRESS.POST_CODE, ADDRESS.DATE_LAST_CHANGED,                       
                  PHONES.NUM
            order by RECORDS.ID
        ";

        $reData = $this->db->select(DB::raw($query));

        if (count($reData)) {
            Settings::set('sync_range', $range);
        } else {
            Settings::set('sync_range', 0);
        }

        $this->output->writeln('Processed records ' . $id . ' through ' . $range);

        return $reData;
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['reset', null, InputOption::VALUE_NONE, 'Starts a sync from the beginning', null],
            ['limit', null, InputOption::VALUE_OPTIONAL, 'Number of records per type to import', null],
        ];
    }
}
