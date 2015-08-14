<?php
/**
 * Created by PhpStorm.
 * User: Hash
 * Date: 01-08-2015
 * Time: 21:14
 */

namespace App\CN\CNEvents;



class EventsRepository {

    /*
     * Method fetches the events
     * @return mixed
     */
    public function retrieveSentMessages()
    {
        return Events::simplePaginate(5);
    }

    /*
     * Method to create event
     *
     */
    public function createEvents()
    {
        $event = new Events();


        $event->event_title =Input::get('event_title');

        $event->event_desc = Input::get('event_desc');

        $event->event_date =Input::get('event_date');

        $event->event_time =Input::get('event_time');

        $event->user_id =Input::get('user_id');

        /*$user->fill(Input::all());*/
        try {

            $event->save();

        }catch (Exception $e){

            return response()->json(['error' => 'Event could not be created'], HttpResponse::HTTP_CONFLICT);

        }

    }


}