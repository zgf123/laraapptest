<?php
namespace App\models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;

trait ActiveUserHelper
{
    protected $users = [];

    protected $topic_weight = 4;
    protected $reply_weight = 1;
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_minutes = 65;
    protected $pass_days = 7;
    protected $user_number = 6;
    
    public function getActiveUsers(){
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function(){
            return $this->calculateActiveUsers();
        });
    }

    private function calculateActiveUsers(){
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        $users = array_sort($this->users,function($user){
            return $user['score'];
        });

        $users = array_reverse($users, true);
        
        $users = array_slice($users, 0, $this->user_number, true);

        $active_users = collect();

        foreach($users as  $user_id => $user){
            $user = $this->find($user_id);
            if($user){
                $active_users->push($user);
            }
        }

        return $active_users;
    }

    private function calculateTopicScore(){
        $topic_users = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
                                     ->groupBy('user_id')
                                     ->get();
        foreach($topic_users as $value){
            $topic_score = $value->topic_count * $this->topic_weight;
            $this->users[$value->user_id]['score'] = $topic_score;
        }
    }

    private function calculateReplyScore(){
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
                                     ->groupBy('user_id')
                                     ->get();
        foreach($reply_users as $value){
            $reply_score = $value->reply_count * $this->reply_weight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $reply_score;
            } else {
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }

    public function calculateAndCacheActiveUsers(){
        $active_users = $this->calculateActiveUsers();
        $this->cacheActiveUsers($active_users);
    }

    private function cacheActiveUsers($active_users){
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_minutes);
    }
    
}