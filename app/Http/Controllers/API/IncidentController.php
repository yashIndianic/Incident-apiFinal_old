<?php

namespace App\Http\Controllers\API;

use App\Incident;
use App\Http\Controllers\Controller;
use App\Http\Resources\CEOResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Http\Requests\IncidentRequest;

class IncidentController extends Controller {

    /**
     * @desc Create new Incident
     * @param IncidentRequest $request
     * @return type
     */
    public function createIncident(IncidentRequest $request) {
        $rawPostData = file_get_contents("php://input");
        $data = json_decode($rawPostData, true);
        $incident = new Incident();
        $incident->title = $data['title'];
        $incident->category_id = $data['category'];
        $incident->comments = $data['comments'];
        $incident->incident_date = gmdate('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['incidentDate'])));
        $incident->created_at = (!is_null($data['createDate'])) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['createDate']))) : \Carbon\Carbon::now();
        $incident->updated_at = (!is_null($data['modifyDate'])) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['modifyDate']))) : \Carbon\Carbon::now();
        $incident->save();
        $location = new \App\Location();
        $location->latitude = $data['location']['latitude'];
        $location->longitude = $data['location']['longitude'];
        $location->incident_id = $incident->id;
        $location->save();
        foreach ($data['people'] as $people) {
            $user = new \App\User();
            $user->name = $people['name'];
            $user->type = $people['type'];
            $user->incident_id = $incident->id;
            $user->save();
        }

        $incidents = $incident->refresh();


        return $data = (new \App\Http\Resources\IncidentResource($incidents));
    }

    /**
     * @desc For Get all Incident
     * @return type
     */
    public function getAllIncident() {
        $incidents = Incident::with('locationsss', 'people')->get();
        return \App\Http\Resources\IncidentResource::collection($incidents);
    }

}
