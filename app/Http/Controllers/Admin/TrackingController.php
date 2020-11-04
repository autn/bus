<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class TrackingController extends CRUD
{
    public function index()
    {
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin') => backpack_url('dashboard'),
            'Trackings' => false,
        ];
        $results = [];

        $request = request('input');
        if ($request) {
            $items = explode("\r\n", trim($request));

            foreach ($items as $key => $item) {
                $raw = explode(',', trim($item));
                $results[$key] = [
                    'item' => $raw[0] . ', ' . $raw[1],
                    'time' => date('Y-m-d H:i:s', $raw[2]),
                ];
                if (isset($items[$key + 1])) {
                    $results[$key]['distance'] = $this->getDistance($items[$key], $items[$key + 1]);
                    $results[$key]['speed'] = $this->getSpeed($items[$key], $items[$key + 1]);
                }
            }
        }
        $this->data['input'] = $request;
        $this->data['results'] = $results;

        return view(backpack_view('trackings'), $this->data);
    }

    public function getDistance($from, $to)
    {
        $fromData = explode(',', trim($from));
        $toData = explode(',', trim($to));
        return $this->distance($fromData[0], $fromData[1], $toData[0], $toData[1], 'M');
    }

    public function getSpeed($from, $to)
    {
        $distance = $this->getDistance($from, $to);
        $fromData = explode(',', trim($from));
        $toData = explode(',', trim($to));
        $duration = $toData[2] - $fromData[2];

        if ($distance == 0 || $duration == 0) {
            return 0;
        }

        return ($distance / $duration) * 3.6;
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1))
                * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == 'M') {
                return ($miles * 1.609344 * 1000);
            } elseif ($unit == 'N') {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
}
