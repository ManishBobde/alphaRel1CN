<?php
/**
 * Created by PhpStorm.
 * User: Hash
 * Date: 01-08-2015
 * Time: 21:14
 */

namespace App\CN\CNNews;


class NewsRepository {

    /*
     * Method fetches the events
     * @return mixed
     */
    public function retrieveNews()
    {
        return News::simplePaginate(5);
    }

    /*
     * Method to create event
     *
     */
    public function createNews()
    {
        $news = new News();


        $news->news_title = Input::get('news_title');

        $news->news_desc = Input::get('news_desc');

        $news->user_id = Input::get('user_id');

        /*$user->fill(Input::all());*/
        try {

            $news->save();

        } catch (Exception $e) {

            return response()->json(['error' => 'News could not be created'], HttpResponse::HTTP_CONFLICT);

        }
    }


}