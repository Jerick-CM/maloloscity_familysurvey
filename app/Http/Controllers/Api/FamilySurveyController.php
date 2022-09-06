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

        return response()->json([
            'success' => true
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

            $individaul_lifecyle_risk = IndividualLifeCycleRisk::where('information_id', $respondent->id)->first();

            $individaul_lifecyle_risk->pregnancy_and_birth = $request->individual_lifecycle_risk['pregnancy_and_birth'];
            $individaul_lifecyle_risk->no_prenatal_checkup = $request->individual_lifecycle_risk['no_prenatal_checkup'];
            $individaul_lifecyle_risk->abortion = $request->individual_lifecycle_risk['abortion'];
            $individaul_lifecyle_risk->miscarriage = $request->individual_lifecycle_risk['miscarriage'];
            $individaul_lifecyle_risk->death_ofa_child = $request->individual_lifecycle_risk['death_ofa_child'];

            $individaul_lifecyle_risk->no_postnatal_checkup = $request->individual_lifecycle_risk['no_postnatal_checkup'];
            $individaul_lifecyle_risk->kid_with_disability = $request->individual_lifecycle_risk['kid_with_disability'];
            $individaul_lifecyle_risk->no_vaccine_in_childhood = $request->individual_lifecycle_risk['no_vaccine_in_childhood'];
            $individaul_lifecyle_risk->familymember_disability_dueto_accident_sickness = $request->individual_lifecycle_risk['familymember_disability_dueto_accident_sickness'];
            $individaul_lifecyle_risk->death_dueto_accident_disaster = $request->individual_lifecycle_risk['death_dueto_accident_disaster'];

            $individaul_lifecyle_risk->familymember_severe_or_longtime_sickness = $request->individual_lifecycle_risk['familymember_severe_or_longtime_sickness'];
            $individaul_lifecyle_risk->no_awarded_medicalservice_healthcenter_or_hospital = $request->individual_lifecycle_risk['no_awarded_medicalservice_healthcenter_or_hospital'];
            $individaul_lifecyle_risk->not_able_to_buy_medicine = $request->individual_lifecycle_risk['not_able_to_buy_medicine'];
            $individaul_lifecyle_risk->familymember_death_dueto_sickness = $request->individual_lifecycle_risk['familymember_death_dueto_sickness'];
            $individaul_lifecyle_risk->not_ableto_enrol_in_daycare_preschool = $request->individual_lifecycle_risk['not_ableto_enrol_in_daycare_preschool'];

            $individaul_lifecyle_risk->not_ableto_enrol_in_elementary = $request->individual_lifecycle_risk['not_ableto_enrol_in_elementary'];
            $individaul_lifecyle_risk->not_ableto_enrol_in_highschool = $request->individual_lifecycle_risk['not_ableto_enrol_in_highschool'];
            $individaul_lifecyle_risk->not_ableto_enrol_in_college = $request->individual_lifecycle_risk['not_ableto_enrol_in_college'];
            $individaul_lifecyle_risk->stop_in_school_or_dropout_in_elementary_or_highschool = $request->individual_lifecycle_risk['stop_in_school_or_dropout_in_elementary_or_highschool'];
            $individaul_lifecyle_risk->drug_addiction = $request->individual_lifecycle_risk['drug_addiction'];

            $individaul_lifecyle_risk->teen_pregnancy_below_18yearsold = $request->individual_lifecycle_risk['teen_pregnancy_below_18yearsold'];
            $individaul_lifecyle_risk->ranaway_from_home = $request->individual_lifecycle_risk['ranaway_from_home'];
            $individaul_lifecyle_risk->familymember_in_abroad = $request->individual_lifecycle_risk['familymember_in_abroad'];
            $individaul_lifecyle_risk->working_in_municipality_or_city = $request->individual_lifecycle_risk['working_in_municipality_or_city'];
            $individaul_lifecyle_risk->separated_with_husbandwife = $request->individual_lifecycle_risk['separated_with_husbandwife'];

            $individaul_lifecyle_risk->death_of_husbandwife = $request->individual_lifecycle_risk['death_of_husbandwife'];
            $individaul_lifecyle_risk->insufficient_food_security = $request->individual_lifecycle_risk['insufficient_food_security'];
            $individaul_lifecyle_risk->insufficient_clean_drinkable_water = $request->individual_lifecycle_risk['insufficient_clean_drinkable_water'];
            $individaul_lifecyle_risk->home_made_of_lightsalvagable_material = $request->individual_lifecycle_risk['home_made_of_lightsalvagable_material'];
            $individaul_lifecyle_risk->scarcity_of_cleanbathroom = $request->individual_lifecycle_risk['scarcity_of_cleanbathroom'];

            $individaul_lifecyle_risk->save();

            $economic_risk = EconomicRisk::where('information_id', $respondent->id)->first();

            $economic_risk->no_work = $request->economic_risk['no_work'];
            $economic_risk->work_notalign_to_profession = $request->economic_risk['work_notalign_to_profession'];
            $economic_risk->experienced_workaccident = $request->economic_risk['experienced_workaccident'];
            $economic_risk->unpaid_loans = $request->economic_risk['unpaid_loans'];
            $economic_risk->victim_of_scamming = $request->economic_risk['victim_of_scamming'];

            $economic_risk->victim_of_illegalrecruitment = $request->economic_risk['victim_of_illegalrecruitment'];
            $economic_risk->change_of_cityaddress = $request->economic_risk['change_of_cityaddress'];
            $economic_risk->no_lifeinsurance = $request->economic_risk['no_lifeinsurance'];
            $economic_risk->no_healthinsurance = $request->economic_risk['no_healthinsurance'];
            $economic_risk->working_without_sss_or_gsis = $request->economic_risk['working_without_sss_or_gsis'];

            $economic_risk->bankrupt_in_business = $request->economic_risk['bankrupt_in_business'];
            $economic_risk->mortgage_of_posessions = $request->economic_risk['mortgage_of_posessions'];
            $economic_risk->loaned_money_from_relatives = $request->economic_risk['loaned_money_from_relatives'];
            $economic_risk->family_elder_without_pension = $request->economic_risk['family_elder_without_pension'];

            $economic_risk->save();

            $environmental_disaster_risk = EnvironmentAndDisasterRisk::where('information_id', $respondent->id)->first();

            $environmental_disaster_risk->victim_of_housefire = $request->environmental_and_disaster_risk['victim_of_housefire'];
            $environmental_disaster_risk->victim_of_typoon_drought = $request->environmental_and_disaster_risk['victim_of_typoon_drought'];
            $environmental_disaster_risk->victim_of_earthquake_volcanic_eruption = $request->environmental_and_disaster_risk['victim_of_earthquake_volcanic_eruption'];
            $environmental_disaster_risk->death_of_family_via_calamity = $request->environmental_and_disaster_risk['death_of_family_via_calamity'];
            $environmental_disaster_risk->house_demolition = $request->environmental_and_disaster_risk['house_demolition'];

            $environmental_disaster_risk->resided_in_relocation_resettlement = $request->environmental_and_disaster_risk['resided_in_relocation_resettlement'];
            $environmental_disaster_risk->save();

            $social_governance_risk = SocialAndGovernanceRisk::where('information_id', $respondent->id)->first();

            $social_governance_risk->victim_of_burglary = $request->social_and_governance_risk['victim_of_burglary'];
            $social_governance_risk->experienced_buglary_holdup = $request->social_and_governance_risk['experienced_buglary_holdup'];
            $social_governance_risk->experienced_sexual_assult = $request->social_and_governance_risk['experienced_sexual_assult'];
            $social_governance_risk->qna_knowledge_with_youth_gangs = $request->social_and_governance_risk['qna_knowledge_with_youth_gangs'];
            $social_governance_risk->death_of_family_due_to_crime = $request->social_and_governance_risk['death_of_family_due_to_crime'];
            $social_governance_risk->witnessed_actual_crime_in_comminity = $request->social_and_governance_risk['witnessed_actual_crime_in_comminity'];
            $social_governance_risk->election_violence = $request->social_and_governance_risk['election_violence'];
            $social_governance_risk->fear_of_disturbance_dueto_election = $request->social_and_governance_risk['fear_of_disturbance_dueto_election'];
            $social_governance_risk->rebellion_against_government = $request->social_and_governance_risk['rebellion_against_government'];
            $social_governance_risk->rebellion_of_military = $request->social_and_governance_risk['rebellion_of_military'];

            $social_governance_risk->violence_at_home = $request->social_and_governance_risk['violence_at_home'];
            $social_governance_risk->violence_to_minor = $request->social_and_governance_risk['violence_to_minor'];
            $social_governance_risk->child_labor = $request->social_and_governance_risk['child_labor'];
            $social_governance_risk->corruption_in_government = $request->social_and_governance_risk['corruption_in_government'];
            $social_governance_risk->nonparticipant_in_comunity_activities = $request->social_and_governance_risk['nonparticipant_in_comunity_activities'];
            $social_governance_risk->nonvoter = $request->social_and_governance_risk['nonvoter'];
            $social_governance_risk->non_membership_inany_organization = $request->social_and_governance_risk['non_membership_inany_organization'];
            $social_governance_risk->claim_land_of_others = $request->social_and_governance_risk['claim_land_of_others'];
            $social_governance_risk->harmful_tradition_to_people = $request->social_and_governance_risk['harmful_tradition_to_people'];
            $social_governance_risk->religious_misunderstanding_dispute = $request->social_and_governance_risk['religious_misunderstanding_dispute'];

            $social_governance_risk->experienced_slow_justicesystem = $request->social_and_governance_risk['experienced_slow_justicesystem'];
            $social_governance_risk->discrimination_in_ethnicity_gender = $request->social_and_governance_risk['discrimination_in_ethnicity_gender'];
            $social_governance_risk->tribal_dispute = $request->social_and_governance_risk['tribal_dispute'];
            $social_governance_risk->other_harms_to_family = $request->social_and_governance_risk['other_harms_to_family'];

            $social_governance_risk->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }
        DB::commit();

        return response()->json([
            'success' => true
        ]);
    }


    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            /* delete respondent */
            $respondent = RespondentsInformation::findOrfail($id);
            /* delete individual life cycle risk */
            $individaul_lifecyle_risk = IndividualLifeCycleRisk::where('information_id', $respondent->id)->first();
            $individaul_lifecyle_risk->delete();
            /* delete economic risk */
            $economic_risk = EconomicRisk::where('information_id', $respondent->id)->first();
            $economic_risk->delete();
            /* delete environmental disaster risk */
            $environmental_disaster_risk = EnvironmentAndDisasterRisk::where('information_id', $respondent->id)->first();
            $environmental_disaster_risk->delete();
            /* delete social and governance risk */
            $social_governance_risk = SocialAndGovernanceRisk::where('information_id', $respondent->id)->first();
            $social_governance_risk->delete();

            $respondent->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }
        DB::commit();

        return response()->json([
            'success' => true
        ]);
    }


    public function fetch(Request $request)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;
        $reqs = RespondentsInformation::query();
        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
            }
        }

        $reqs = $reqs->where(function ($query) use ($params) {

            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['full_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['first_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['last_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['middle_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['barangay', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $count = RespondentsInformation::where($params['filterField'], $params['filterValue'])->count();
            } else {
                $count = RespondentsInformation::count();
            }
        } else {
            $count = RespondentsInformation::count();
        }

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
