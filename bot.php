<?php
    require 'rb.php';
    require_once "vendor/autoload.php";
    require 'db.php';

    $token = "token";

    $bot = new \TelegramBot\Api\Client($token);

    // ---------------------------------------------------------------------------
    // | 0.00 - 0.07 | Factory New
    // ---------------------------------------------------------------------------
    // | 0.07 - 0.15 | Minimal Wear
    // ---------------------------------------------------------------------------
    // | 0.15 - 0.38 | Field-Tested
    // ---------------------------------------------------------------------------
    // | 0.38 - 0.45 | Well Worn
    // ---------------------------------------------------------------------------
    // | 0.45 - 1.00 | Battle Scarred
    // ---------------------------------------------------------------------------

    for(;;){
        $numbers = R::getAll('SELECT * FROM number');
        $number = $numbers[0]['number'];
        settype($number, "int");
        $floatguns = R::getAll( 'SELECT * FROM floatguns' );
        if($number == count($floatguns) || $number > count($floatguns)){
            $number = 0;
            $cat = R::load('number', 1);
            $cat->number = $number;
            R::store($cat);
        }

        $fromfloat = ( float ) $floatguns[$number]['fromfloat'];
        $tofloat = ( float ) $floatguns[$number]['tofloat'];
        if($floatguns[$number]['notes']){
            $notes = $floatguns[$number]['notes'];
        }
        else{
            $notes = '-';
        }

        $steam = file_get_contents('https://steamcommunity.com/market/listings/730/' . $floatguns[$number]['name'] . '/render/?query=&start=0&count=100&language=english&currency=1');
        $steam_decoded = json_decode($steam);
        $fullgunname = $floatguns[$number]['name'];
        $paintseeds = explode(", ", $floatguns[$number]['paintseed']);
        $k = 1;
        foreach ($steam_decoded->listinginfo as $val) {
            if ($val->converted_price == 0){
                continue;
            }
            if ($k == 1){
                $startprice = round($val->converted_price*0.763137, 2);
            }

            $inspectlink = str_replace("%listingid%", $val->listingid, $val->asset->market_actions[0]->link);
            $inspectlink = str_replace("%assetid%", $val->asset->id, $inspectlink);
            $getfloat = file_get_contents('https://api.csgofloat.com/?url=' . $inspectlink);
            $getfloat_decoded = json_decode($getfloat);

            $imageurl = $getfloat_decoded->iteminfo->imageurl;
            $phase = '-';
            $paintseed = '-';
            if($floatguns[$number]['phase'] == '-'){
                $paintseed = $getfloat_decoded->iteminfo->paintseed;
                if($floatguns[$number]['paintseed'] != ''){
                    if(count($paintseeds) == 1){
                        if($paintseed != $floatguns[$number]['paintseed']){
                            $k++;
                            continue;
                        }
                    }
                    elseif(!in_array($paintseed, $paintseeds)) {
                        $k++;
                        continue;
                    }
                }
            }
            else{
                $phase = explode("am_", explode("_light_", $imageurl)[0])[1];
                if(!stristr($phase, $floatguns[$number]['phase'])){
                    $k++;
                    continue;
                }
            }

            $floatvalue = $getfloat_decoded->iteminfo->floatvalue;
            if($floatvalue > $fromfloat and $floatvalue < $tofloat){
                $gunsfloats = R::getAll( 'SELECT * FROM dbfloatguns WHERE gunname = :gunname AND floatvalue = :floatvalue',
                    [':gunname' => $fullgunname,
                    ':floatvalue' => $floatvalue
                ]);

                if (!empty($gunsfloats)){
                    continue;
                }
                else{
                    $book = R::dispense( 'dbfloatguns' );
                    $book->gunname = $fullgunname;
                    $book->floatvalue = $floatvalue;
                    $book->price = $val->converted_price;
                    $id = R::store( $book );

                    $name = str_replace('%20', ' ', $fullgunname);
                    $name = str_replace('%7C', ' | ', $name);
                    $name = str_replace('%28', '(', $name);
                    $name = str_replace('%29', ')', $name);
                    $telmes = R::getAll( 'SELECT * FROM usersid' );
                    $floatvalue = number_format($floatvalue, 15, '.', '');
                    foreach ($telmes as $telid) {
                        $answer = $name . "\nFloat - " . $floatvalue . "\nPaintSeed - " . $paintseed .  "\nPhase - " . $floatguns[$number]['phase'] . "\nNotes - " . $notes . "\nStartprice - " . $startprice . "руб.\n" . 'Price - ' . round($val->converted_price*0.763137, 2) . "руб.\n" .
                         'https://steamcommunity.com/market/listings/730/' . $fullgunname;
                        try{
                            $bot->sendMessage($telid['userid'], $answer);
                        } catch (Exception $e) {
                            continue;
                        }
                    }
                }
            }
            $k++;
        }
        $number++;
        $cat = R::load('number', 1);
        $cat->number = $number;
        R::store($cat);
        sleep(4);
    }
?>