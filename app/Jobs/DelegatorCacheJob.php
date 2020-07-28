<?php

namespace App\Jobs;

use App\Services\Persistence\ValidatorCache;

class DelegatorCacheJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $vote_address;
    public function __construct($vote_address)
    {
        //
        $this->vote_address = $vote_address;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $vote_address = $this->vote_address;
        //
        if(!\Cache::add(sprintf('lock:delegator-address-%s', $vote_address), true, 10)){
            return;
        }

        \Log::info("[vote_address:{$vote_address}]job==start");
        ValidatorCache::instance()->getDelegator($vote_address, true);

        return;

    }
}
