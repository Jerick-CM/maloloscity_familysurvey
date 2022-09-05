<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FamilySurveyRequest;
use App\Http\Resources\FamilySurveyResource;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\RespondentsInformation;
use App\Models\EconomicRisk;
use App\Models\EnvironmentAndDisasterRisk;
use App\Models\IndividualLifeCycleRisk;
use App\Models\SocialAndGovernanceRisk;

class FamilySurveyController extends Controller
{

    public function index()
    {
        return FamilySurveyResource::collection(RespondentsInformation::orderby('id', 'DESC')->get());
    }

    public function store(FamilySurveyRequest $request)
    {

        Validator::make($request->all(), [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'middle_name' => ['nullable'],
            'barangay' => ['required'],
            'family_position' => ['required'],

        ])->validate();

        DB::beginTransaction();

        try {

            $fullname = $request->first_name . " " . $request->middle_name . " " . $request->last_name . " " . $request->name_suffix;

            $RespondentsInformation = RespondentsInformation::create(
                [
                    'user_id' => Auth::guard('web')->user()->id,
                    'full_name' => $fullname,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'name_suffix' => $request->name_suffix,
                    'family_position' => $request->family_position,
                    'number_of_children' => $request->number_of_children,
                    'number_of_people_in_household' => $request->number_of_people_in_household,
                    'four_ps_beneficiary' => $request->four_ps_beneficiary,
                    'four_ps_beneficiary_id' => $request->four_ps_beneficiary_id,
                    'four_ps_beneficiary_date' => $request->four_ps_beneficiary_date,
                    'region' => $request->region,
                    'province' => $request->lalawigan,
                    'city' => $request->municipality,
                    'barangay' => $request->barangay,
                    'sitio' => $request->sitio,
                    'purok' => $request->purok,
                ]
            );

            IndividualLifeCycleRisk::create(
                [
                    'information_id' =>  $RespondentsInformation->id,

                    'pregnancy_and_birth' =>  $request->checklist[0],
                    'no_prenatal_checkup' =>  $request->checklist[1],
                    'abortion' =>  $request->checklist[2],
                    'miscarriage' =>  $request->checklist[3],
                    'death_ofa_child' =>  $request->checklist[4],
                    'no_postnatal_checkup' =>  $request->checklist[5],
                    'kid_with_disability' =>  $request->checklist[6],
                    'no_vaccine_in_childhood' =>  $request->checklist[7],
                    'familymember_disability_dueto_accident_sickness' =>  $request->checklist[8],
                    'death_dueto_accident_disaster' =>  $request->checklist[9],

                    'familymember_severe_or_longtime_sickness' =>  $request->checklist[10],
                    'no_awarded_medicalservice_healthcenter_or_hospital' =>  $request->checklist[11],
                    'not_able_to_buy_medicine' =>  $request->checklist[12],
                    'familymember_death_dueto_sickness' =>  $request->checklist[13],
                    'not_ableto_enrol_in_daycare_preschool' =>  $request->checklist[14],
                    'not_ableto_enrol_in_elementary' =>  $request->checklist[15],
                    'not_ableto_enrol_in_highschool' =>  $request->checklist[16],
                    'not_ableto_enrol_in_college' =>  $request->checklist[17],
                    'stop_in_school_or_dropout_in_elementary_or_highschool' =>  $request->checklist[18],
                    'drug_addiction' =>  $request->checklist[19],

                    'teen_pregnancy_below_18yearsold' =>  $request->checklist[20],
                    'ranaway_from_home' =>  $request->checklist[21],
                    'familymember_in_abroad' =>  $request->checklist[22],
                    'working_in_municipality_or_city' =>  $request->checklist[23],
                    'separated_with_husbandwife' =>  $request->checklist[24],
                    'death_of_husbandwife' =>  $request->checklist[25],
                    'insufficient_food_security' =>  $request->checklist[26],
                    'insufficient_clean_drinkable_water' =>  $request->checklist[27],
                    'home_made_of_lightsalvagable_material' =>  $request->checklist[28],
                    'scarcity_of_cleanbathroom' =>  $request->checklist[29],
                ]
            );
            EconomicRisk::create(
                [
                    'information_id' =>  $RespondentsInformation->id,
                    'no_work' =>  $request->checklist[30],
                    'work_notalign_to_profession' =>  $request->checklist[31],
                    'experienced_workaccident' =>  $request->checklist[32],
                    'unpaid_loans' =>  $request->checklist[33],
                    'victim_of_scamming' =>  $request->checklist[34],
                    'victim_of_illegalrecruitment' =>  $request->checklist[35],
                    'change_of_cityaddress' =>  $request->checklist[36],
                    'no_lifeinsurance' =>  $request->checklist[37],
                    'no_healthinsurance' =>  $request->checklist[38],
                    'working_without_sss_or_gsis' =>  $request->checklist[39],
                    'bankrupt_in_business' =>  $request->checklist[40],
                    'mortgage_of_posessions' =>  $request->checklist[41],
                    'loaned_money_from_relatives' =>  $request->checklist[42],
                    'family_elder_without_pension' =>  $request->checklist[43],
                ]
            );
            EnvironmentAndDisasterRisk::create(
                [
                    'information_id' =>  $RespondentsInformation->id,
                    'victim_of_housefire' =>  $request->checklist[44],
                    'victim_of_typoon_drought' =>  $request->checklist[45],
                    'victim_of_earthquake_volcanic_eruption' =>  $request->checklist[46],
                    'death_of_family_via_calamity' =>  $request->checklist[47],
                    'house_demolition' =>  $request->checklist[48],
                    'resided_in_relocation_resettlement' =>  $request->checklist[49],
                ]
            );
            SocialAndGovernanceRisk::create(
                [
                    'information_id' =>  $RespondentsInformation->id,
                    'victim_of_burglary' =>  $request->checklist[50],
                    'experienced_buglary_holdup' =>  $request->checklist[51],
                    'experienced_sexual_assult' =>  $request->checklist[52],
                    'qna_knowledge_with_youth_gangs' =>  $request->checklist[53],
                    'death_of_family_due_to_crime' =>  $request->checklist[54],
                    'witnessed_actual_crime_in_comminity' =>  $request->checklist[55],
                    'election_violence' =>  $request->checklist[56],
                    'fear_of_disturbance_dueto_election' =>  $request->checklist[57],
                    'rebellion_against_government' =>  $request->checklist[58],
                    'rebellion_of_military' =>  $request->checklist[59],

                    'violence_at_home' =>  $request->checklist[60],
                    'violence_to_minor' =>  $request->checklist[61],
                    'child_labor' =>  $request->checklist[62],
                    'corruption_in_government' =>  $request->checklist[63],
                    'nonparticipant_in_comunity_activities' =>  $request->checklist[64],
                    'nonvoter' =>  $request->checklist[65],
                    'non_membership_inany_organization' =>  $request->checklist[66],
                    'claim_land_of_others' =>  $request->checklist[67],
                    'harmful_tradition_to_people' =>  $request->checklist[68],
                    'religious_misunderstanding_dispute' =>  $request->checklist[69],

                    'experienced_slow_justicesystem' =>  $request->checklist[70],
                    'discrimination_in_ethnicity_gender' =>  $request->checklist[71],
                    'tribal_dispute' =>  $request->checklist[72],
                    'other_harms_to_family' =>  $request->checklist[73],
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }
        DB::commit();

        // $user = User::findOrfail($request->user_id);
        // event(new UserLogsEvent($request->user_id, Logs::TYPE_CREATE_BUSINESS, [
        //     'email'  =>   $user->email,
        //     'business_name'  =>   $request->business_name,
        // ]));
        // return new BusinessResource($RespondentsInformation);

        return response()->json([
            'success' => true
            // 'data' => $data,
        ]);
    }


    public function show(Request $request, $id)
    {
        $data = RespondentsInformation::with('individual_lifecycle_risk')
            ->with('economic_risk')
            ->with('environmental_and_disaster_risk')
            ->with('social_and_governance_risk')
            ->where('id', $id)->first();
        return response()->json(['data' => $data]);
    }


    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'middle_name' => ['nullable'],
            'barangay' => ['required'],
            'family_position' => ['required'],
        ])->validate();

        DB::beginTransaction();
        try {

            $fullname = $request->first_name . " " . $request->middle_name . " " . $request->last_name . " " . $request->name_suffix;

            $respondent = RespondentsInformation::findOrfail($id);
            $respondent->full_name = $fullname;
            $respondent->first_name = $request->first_name;
            $respondent->middle_name = $request->middle_name;
            $respondent->last_name = $request->last_name;
            $respondent->name_suffix = $request->name_suffix;
            $respondent->family_position = $request->family_position;
            $respondent->number_of_children = $request->number_of_children;
            $respondent->number_of_people_in_household = $request->number_of_people_in_household;
            $respondent->four_ps_beneficiary = $request->four_ps_beneficiary;
            $respondent->four_ps_beneficiary_id = $request->four_ps_beneficiary_id;
            $respondent->four_ps_beneficiary_date = $request->four_ps_beneficiary_date;
            $respondent->region = $request->region;
            $respondent->province = $request->lalawigan;
            $respondent->city = $request->municipality;
            $respondent->barangay = $request->barangay;
            $respondent->sitio = $request->sitio;
            $respondent->purok = $request->purok;
            $respondent->update();


            $individaul_lifecyle_risk =IndividualLifeCycleRisk::where('information_id',$respondent->id)->first();

            $individaul_lifecyle_risk->pregnancy_and_birth = $request->individual_lifecycle_risk['pregnancy_and_birth'];
            $individaul_lifecyle_risk->update();


            //         'pregnancy_and_birth' =>  $request->checklist[0],
            //         'no_prenatal_checkup' =>  $request->checklist[1],
            //         'abortion' =>  $request->checklist[2],
            //         'miscarriage' =>  $request->checklist[3],
            //         'death_ofa_child' =>  $request->checklist[4],
            //         'no_postnatal_checkup' =>  $request->checklist[5],
            //         'kid_with_disability' =>  $request->checklist[6],
            //         'no_vaccine_in_childhood' =>  $request->checklist[7],
            //         'familymember_disability_dueto_accident_sickness' =>  $request->checklist[8],
            //         'death_dueto_accident_disaster' =>  $request->checklist[9],

            //         'familymember_severe_or_longtime_sickness' =>  $request->checklist[10],
            //         'no_awarded_medicalservice_healthcenter_or_hospital' =>  $request->checklist[11],
            //         'not_able_to_buy_medicine' =>  $request->checklist[12],
            //         'familymember_death_dueto_sickness' =>  $request->checklist[13],
            //         'not_ableto_enrol_in_daycare_preschool' =>  $request->checklist[14],
            //         'not_ableto_enrol_in_elementary' =>  $request->checklist[15],
            //         'not_ableto_enrol_in_highschool' =>  $request->checklist[16],
            //         'not_ableto_enrol_in_college' =>  $request->checklist[17],
            //         'stop_in_school_or_dropout_in_elementary_or_highschool' =>  $request->checklist[18],
            //         'drug_addiction' =>  $request->checklist[19],

            //         'teen_pregnancy_below_18yearsold' =>  $request->checklist[20],
            //         'ranaway_from_home' =>  $request->checklist[21],
            //         'familymember_in_abroad' =>  $request->checklist[22],
            //         'working_in_municipality_or_city' =>  $request->checklist[23],
            //         'separated_with_husbandwife' =>  $request->checklist[24],
            //         'death_of_husbandwife' =>  $request->checklist[25],
            //         'insufficient_food_security' =>  $request->checklist[26],
            //         'insufficient_clean_drinkable_water' =>  $request->checklist[27],
            //         'home_made_of_lightsalvagable_material' =>  $request->checklist[28],
            //         'scarcity_of_cleanbathroom' =>  $request->checklist[29],
            //     ]
            // );


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }
        DB::commit();

        // $familysurvey->update($request->validated());

        // return new FamilySurveyResource($familysurvey);

        return response()->json([
            'success' => true
            // 'data' => $data,
        ]);
    }


    public function destroy(RespondentsInformation $familysurvey)
    {
        $familysurvey->delete();

        return response()->noContent();
    }


    public function fetch(Request $request)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;
        $reqs = RespondentsInformation::query();
        if (isset($params['filterField'])) {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs = $reqs->where(function ($query) use ($params) {

            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['full_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['first_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['last_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['middle_name', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();
        $count = RespondentsInformation::count();

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }

    public function getSearchfield(Request $request)
    {
        $data =  RespondentsInformation::select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
        return response()->json([
            'data' => $data,
        ]);
    }
}
