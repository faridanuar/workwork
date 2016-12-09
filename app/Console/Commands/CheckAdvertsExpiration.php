<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Mail;

use Carbon\Carbon;

use App\Advert;
use App\Employer;
use App\User;

use App\Contracts\Search;

class CheckAdvertsExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adverts:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking all adverts plan expiration date validity';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Search $search)
    {
        $adverts = Advert::all();

        foreach($adverts as $advert)
        {
            $todaysDate = Carbon::now();
            $endDate = $advert->plan_ends_at;
            $expDate =  $todaysDate->diffInDays($endDate, false);

            if($expDate < 0){

                if($advert->published != 0)
                {
                    $advert->published = 0;
                    $advert->save();

                    $config = config('services.algolia');
                    $index = $config['index'];
                    $indexFromAlgolia = $search->index($index);
                    $object = $indexFromAlgolia->deleteObject($advert->id);
                }

            }elseif($expDate > 0 && $expDate < 4){

                $advert->about_to_expire = 1;
                $advert->save();

                $user = $advert->employer->user;

                if($user->verified === 1)
                {

                    $emailView = 'mail.notify_expiration';

                    // fetch mailgun attributes from SERVICES file
                    $config = config('services.mailgun');
                    // applications domain
                    $domain = $config['sender'];
                    // fetch website provided url
                    $website = $config['site_url'];

                    // set the values in array
                    $data = ['user' => $user, 'website' => $website, 'advert' => $advert];
                    $parameter = ['user' => $user, 'domain' => $domain];

                    // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
                    Mail::send($emailView, $data, function ($message) use ($parameter) {

                        // Recipient Test Email => $recipient = "farid@pocketpixel.com";

                        // get the necessary required values for mailgun
                        $appDomain = $parameter['domain'];
                        $recipient = $parameter['user']->email;
                        $recipientName = $parameter['user']->name;

                        // set email sender stmp url and sender name
                        $message->from($appDomain, 'WorkWork');

                        // set email recepient and subject
                        $message->to($recipient, $recipientName)->subject('Notice');
                    });
                }

            }
        }

        $this->info('All Checked & Validated!');
    }
}
