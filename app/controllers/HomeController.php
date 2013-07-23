<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
    
       protected  $commentFeed; 

       public function showWelcome()
	{
		return View::make('hello');
	}
        
        
        public function getComments($url = null, $invalid = null)
        {
                $url  = "http://www.reddit.com/r/GiftofGames/comments/1ivs8m/offersteamace_of_spades/.json";
//                $url = "http://www.reddit.com/r/SteamGameSwap/comments/1iwo6k/h_some_inventory_games_i_try_to_get_rid_off_w/.json";
                
                if(!$this->validateUrl($url))
                {
                    return json_encode(array('success'=> false, 'error'=>'url is invalid'));
                }
                
                $data = $this->commentFeed; 
                
                $dataArray = json_decode($data);
                
                $winner = $this->selectWinner($dataArray[1]->data->children, $invalid);
                
                $post = $this->getPost($dataArray[0]);
                
                $responce = array('success'=> true, 'winner'=>$winner,'post'=>$post);
                
                echo "<pre>";
                print_r($responce);
                exit;
                
        }
        
        
        private function  selectWinner($data, $cantBe = null)
        {
            
            // generate random number
            $randInt = rand(0, count($data));
            // select entry based on  random number
            $winner =  $data[$randInt]->data;
            // just some simple data 
            $winnerInfo = array(
                // $winner->data->
                "postId"    => $winner->id,
                "body"      => $this->cleanHTML($winner->body_html),
                "author"    => $winner->author,
                
            );
            
            return $winnerInfo;
        }
        
        /**
         * gets this post so we can display it to the user. 
         */
        private function getPost($data)
        {   

            $pData = $data->data->children[0]->data;
            return array(
                "title"     =>  $pData->title,
                "author"    =>  $pData->author,
                "body"      =>  $this->cleanHTML($pData->selftext_html),
                "url"       =>  $pData->url,
                "status"    =>  $pData->link_flair_text
                
            );  
        }
        
        
        private function cleanHTML($html)
        {   
            // we want to remove all linebreaks, returns & tabs
            $removeWhiteSpace = array("\n","\r","\r\n","\t");
            $noWhiteSpace = str_replace($removeWhiteSpace,"" ,$html);
            
            //  replace double quotes with single quotes
            $cleaned = str_replace('"',"'" ,$noWhiteSpace);
            
            return $cleaned;
        }
        
        private function validateUrl($url)
        {
            $UrlData = parse_url($url);
            $host = $UrlData['host'];
            
            if($host != "www.reddit.com" )
            {
               return false; 
            }
            
            $ch = curl_init($url);  
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            $data = curl_exec($ch);  
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
            curl_close($ch);  
            
            $this->commentFeed = $data; 
            
            if($httpcode>=200 && $httpcode<300){  
                return true;  
            } else {  
                return false;  
            }  
            
        }
        
        
        private function debug($data)
        {
            echo "<pre>";
            print_r($data);
            exit;
        }
}