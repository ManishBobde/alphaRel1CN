<?php
/**
 * Created by PhpStorm.
 * User: Hash
 * Date: 28-07-2015
 * Time: 23:14
 */

namespace App\CN\CNMessages;


use App\CN\CNBuckets\Bucket;
use App\CN\Paginator\MessagePaginator;

class MessageRepository  implements MessageInterface {


    /*protected $paginator;

    public function __construct(MessagePaginator $paginator){

        $this->paginator = $paginator;
    }*/

    /**
     * @return mixed
     */
    public function retrieveInboxMessages()
    {
        $messages = Message::where('bucketId', 1)->simplePaginate(5);

        $before=0;$after=0;

        foreach ($messages->toArray() as $key => $value) {

            if ($key === 'from') {

                $before = $messages->toArray()[$key];

            }
            else if($key === 'to'){

                $after = $messages->toArray()[$key];

            }

        }
            return $messages;

    }

    public function retrieveSentMessages()
    {
        return Message::where('bucketId', 2)->simplePaginate(5);
    }

    public function retrieveDraftMessages()
    {
        return Message::where('bucketId', 3)->simplePaginate(5);
    }

    public function retrieveTrashedMessages()
    {
        return Message::where('bucketId', 4)->simplePaginate(5);
    }

    public function submitMessages()
    {
        $message = new Message();

        $message->to =Input::get('msgTo');

        $message->msg_title =Input::get('msgTitle');

        $message->msg_desc = Input::get('msgDesc');

        $message->msg_read =Input::get('msgRead');

        $message->user_id =Input::get('userId');

        $message->bucket_id =Input::get('bucketId');

        /*$user->fill(Input::all());*/
        try {

            $message->save();

        }catch (Exception $e){

            return response()->json(['error' => 'Message could not be composed'], HttpResponse::HTTP_CONFLICT);

        }

    }

    public function trashMessages()
    {

    }

    public function deleteMessages()
    {

    }

    /**
     * Returns UUID of 32 characters
     *
     * @return string
     */
    public function generateUUID()
    {
        $currentTime = (string)microtime(true);
        $randNumber = (string)rand(10000, 1000000);
        $shuffledString = str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
        return md5($currentTime . $randNumber . $shuffledString);
    }

    /**
     * @param $bucketName
     * @return mixed
     */
    public function retrieveMessages($bucketName){

        $bucket = Bucket::where('bucketName',strtolower($bucketName))->get(['bucketType']);

        $bucketType =  array_column($bucket->toArray(),'bucketType');

        $index = 0;

        switch ($bucketType[$index]) {
            case 1:
                return $this->retrieveInboxMessages();
                break;
            case 2:
                return $this->retrieveSentMessages();
                break;
            case 3:
                return $this->retrieveDraftMessages();
                break;
            case 4:
                return $this->retrieveTrashedMessages();
                break;
        }

    }

}