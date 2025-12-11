<?php
/**
 * Created by Zaman.
 * User: root
 * Date: 3/24/19
 * Time: 2:57 PM
 */

Route::get('stackholder/get-token/{reg_key}/{password}', 'StakeholderApiController@getToken')->middleware('XssProtection');;