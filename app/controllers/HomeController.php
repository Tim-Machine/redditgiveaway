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
       private $url ;

       /** shows the home page
        *
        * @return type
        */
       public function showWelcome()
	{
		return View::make('hello');
	}

        /**
         *  where all the magic begins
         * delevers json responce with a winner
         * @param type $url
         * @param type $invalid
         * @return type
         */
        public function getComments()
        {

            $url = urldecode($_GET['url']);

            // is a winning number provided?
            // $winningNumber = (isset($_GET['winnum']))? false : $_GET['winnum'];
            $winningNumber = false;
            $invalid = null;
            $this->url = $url;

            $urlJson = $this->urlFormat();

            if(!$this->validateUrl($urlJson))
            {
                return json_encode(array('success'=> false, 'error'=>'Invalid Link'));
            }

            $data = $this->commentFeed;

            $dataArray = json_decode($data);

            if($winningNumber)
            {
                $winner = $this->selectWinnerByNumber($dataArray[1]->data->children, $invalid);
            }
            else{
                $winner = $this->selectWinner($dataArray[1]->data->children, $invalid);
            }

            $post = $this->getPost($dataArray[0]);

            $responce = array('success'=> true, 'winner'=>$winner,'post'=>$post);

            return json_encode($responce);
        }

        /**
         *
         * @param type $data
         * @param type $cantBe
         * @return type
         */
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
                "content"   => (string)$this->cleanHTML($winner->body_html),
                "author"    => $winner->author,

            );

            return $winnerInfo;
        }

        /**
         * select a winning entry that matches the number provided
         * @param  array $data   all comment entries
         * @param  arary $cantBe entry ids that that can not win
         * @return array         data about the winning entry
         */
        private function selectWinnerByNumber($data, $number ,$cantBe = null)
        {
            $possblewinner = array();
            $i = 0;
            foreach($data as $entry){
                if( strpos( $number, $entry->body_html)){
                    $possblewinner->$i = $data[$i];
                    $i++;
                }
            }

            return $this->selectWinner($possblewinner, $cantBe);
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
                "content"   =>  $this->cleanHTML($pData->selftext_html),
                "url"       =>  $pData->url,
                "status"    =>  $pData->link_flair_text

            );
        }

        /**
         * removes all whitespace from the content body & escapes quotes
         * @param  string $html [description]
         * @return [type]       [description]
         */
        private function cleanHTML($html)
        {
            // we want to remove all linebreaks, returns & tabs
            $removeWhiteSpace = array("\n","\r","\r\n","\t");
            $noWhiteSpace = str_replace($removeWhiteSpace,"" ,$html);

            //  replace double quotes with single quotes
            $cleaned = str_replace('"',"\"" , $noWhiteSpace);

            return html_entity_decode($cleaned);
        }

        /**
         * validate that url is 1. from reddit.com 2. Is a valid url on reddit.compact
         * @param  string $url url to be validated
         * @return bool      is valid url ?
         */
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

        /**
         * validates that we are using the JSON fromat from Reddit
         * @return void
         */
        private function urlFormat()
        {
            $urlData = explode('.', $this->url);

            if(count($urlData) > 1 && end($urlData) == 'json')
            {
                return $this->url;
            }
            return $this->url = $this->url.".json";

        }

        /**
         * just a debug tool
         * @param  any $data dumps any var onto the screen.
         * @return void
         */
        private function debug($data)
        {
            echo "<pre>";
            print_r($data);
            exit;
        }
}