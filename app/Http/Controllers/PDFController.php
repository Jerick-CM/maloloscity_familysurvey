<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PDF;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller
{

    public function survey_report($barangay)
    {

        $ilr = DB::table('individual_lifecycle_risks')

            ->select(
                // 1
                DB::raw('sum(pregnancy_and_birth=1) as i1a'),
                DB::raw('sum(pregnancy_and_birth=2) as i1b'),
                DB::raw('sum(pregnancy_and_birth=3) as i1c'),

                DB::raw('sum(no_prenatal_checkup=1) as i2a'),
                DB::raw('sum(no_prenatal_checkup=2) as i2b'),
                DB::raw('sum(no_prenatal_checkup=3) as i2c'),

                DB::raw('sum(abortion=1) as i3a'),
                DB::raw('sum(abortion=2) as i3b'),
                DB::raw('sum(abortion=3) as i3c'),

                DB::raw('sum(miscarriage=1) as i4a'),
                DB::raw('sum(miscarriage=2) as i4b'),
                DB::raw('sum(miscarriage=3) as i4c'),

                DB::raw('sum(death_ofa_child=1) as i5a'),
                DB::raw('sum(death_ofa_child=2) as i5b'),
                DB::raw('sum(death_ofa_child=3) as i5c'),

                DB::raw('sum(no_postnatal_checkup=1) as i6a'),
                DB::raw('sum(no_postnatal_checkup=2) as i6b'),
                DB::raw('sum(no_postnatal_checkup=3) as i6c'),

                DB::raw('sum(kid_with_disability=1) as i7a'),
                DB::raw('sum(kid_with_disability=2) as i7b'),
                DB::raw('sum(kid_with_disability=3) as i7c'),

                DB::raw('sum(no_vaccine_in_childhood=1) as i8a'),
                DB::raw('sum(no_vaccine_in_childhood=2) as i8b'),
                DB::raw('sum(no_vaccine_in_childhood=3) as i8c'),

                DB::raw('sum(familymember_disability_dueto_accident_sickness=1) as i9a'),
                DB::raw('sum(familymember_disability_dueto_accident_sickness=2) as i9b'),
                DB::raw('sum(familymember_disability_dueto_accident_sickness=3) as i9c'),

                DB::raw('sum(death_dueto_accident_disaster=1) as i10a'),
                DB::raw('sum(death_dueto_accident_disaster=2) as i10b'),
                DB::raw('sum(death_dueto_accident_disaster=3) as i10c'),

                // 11

                DB::raw('sum(familymember_severe_or_longtime_sickness=1) as i11a'),
                DB::raw('sum(familymember_severe_or_longtime_sickness=2) as i11b'),
                DB::raw('sum(familymember_severe_or_longtime_sickness=3) as i11c'),

                DB::raw('sum(no_awarded_medicalservice_healthcenter_or_hospital=1) as i12a'),
                DB::raw('sum(no_awarded_medicalservice_healthcenter_or_hospital=2) as i12b'),
                DB::raw('sum(no_awarded_medicalservice_healthcenter_or_hospital=3) as i12c'),

                DB::raw('sum(not_able_to_buy_medicine=1) as i13a'),
                DB::raw('sum(not_able_to_buy_medicine=2) as i13b'),
                DB::raw('sum(not_able_to_buy_medicine=3) as i13c'),

                DB::raw('sum(familymember_death_dueto_sickness=1) as i14a'),
                DB::raw('sum(familymember_death_dueto_sickness=2) as i14b'),
                DB::raw('sum(familymember_death_dueto_sickness=3) as i14c'),

                DB::raw('sum(not_ableto_enrol_in_daycare_preschool=1) as i15a'),
                DB::raw('sum(not_ableto_enrol_in_daycare_preschool=2) as i15b'),
                DB::raw('sum(not_ableto_enrol_in_daycare_preschool=3) as i15c'),

                DB::raw('sum(not_ableto_enrol_in_elementary=1) as i16a'),
                DB::raw('sum(not_ableto_enrol_in_elementary=2) as i16b'),
                DB::raw('sum(not_ableto_enrol_in_elementary=3) as i16c'),

                DB::raw('sum(not_ableto_enrol_in_highschool=1) as i17a'),
                DB::raw('sum(not_ableto_enrol_in_highschool=2) as i17b'),
                DB::raw('sum(not_ableto_enrol_in_highschool=3) as i17c'),

                DB::raw('sum(not_ableto_enrol_in_college=1) as i18a'),
                DB::raw('sum(not_ableto_enrol_in_college=2) as i18b'),
                DB::raw('sum(not_ableto_enrol_in_college=3) as i18c'),

                DB::raw('sum(stop_in_school_or_dropout_in_elementary_or_highschool=1) as i19a'),
                DB::raw('sum(stop_in_school_or_dropout_in_elementary_or_highschool=2) as i19b'),
                DB::raw('sum(stop_in_school_or_dropout_in_elementary_or_highschool=3) as i19c'),

                DB::raw('sum(drug_addiction=1) as i20a'),
                DB::raw('sum(drug_addiction=2) as i20b'),
                DB::raw('sum(drug_addiction=3) as i20c'),

                // 21
                DB::raw('sum(teen_pregnancy_below_18yearsold=1) as i21a'),
                DB::raw('sum(teen_pregnancy_below_18yearsold=2) as i21b'),
                DB::raw('sum(teen_pregnancy_below_18yearsold=3) as i21c'),

                DB::raw('sum(ranaway_from_home=1) as i22a'),
                DB::raw('sum(ranaway_from_home=2) as i22b'),
                DB::raw('sum(ranaway_from_home=3) as i22c'),

                DB::raw('sum(familymember_in_abroad=1) as i23a'),
                DB::raw('sum(familymember_in_abroad=2) as i23b'),
                DB::raw('sum(familymember_in_abroad=3) as i23c'),

                DB::raw('sum(working_in_municipality_or_city=1) as i24a'),
                DB::raw('sum(working_in_municipality_or_city=2) as i24b'),
                DB::raw('sum(working_in_municipality_or_city=3) as i24c'),

                DB::raw('sum(separated_with_husbandwife=1) as i25a'),
                DB::raw('sum(separated_with_husbandwife=2) as i25b'),
                DB::raw('sum(separated_with_husbandwife=3) as i25c'),

                DB::raw('sum(death_of_husbandwife=1) as i26a'),
                DB::raw('sum(death_of_husbandwife=2) as i26b'),
                DB::raw('sum(death_of_husbandwife=3) as i26c'),

                DB::raw('sum(insufficient_food_security=1) as i27a'),
                DB::raw('sum(insufficient_food_security=2) as i27b'),
                DB::raw('sum(insufficient_food_security=3) as i27c'),

                DB::raw('sum(insufficient_clean_drinkable_water=1) as i28a'),
                DB::raw('sum(insufficient_clean_drinkable_water=2) as i28b'),
                DB::raw('sum(insufficient_clean_drinkable_water=3) as i28c'),

                DB::raw('sum(home_made_of_lightsalvagable_material=1) as i29a'),
                DB::raw('sum(home_made_of_lightsalvagable_material=2) as i29b'),
                DB::raw('sum(home_made_of_lightsalvagable_material=3) as i29c'),

                DB::raw('sum(scarcity_of_cleanbathroom=1) as i30a'),
                DB::raw('sum(scarcity_of_cleanbathroom=2) as i30b'),
                DB::raw('sum(scarcity_of_cleanbathroom=3) as i30c'),

            )
            ->join('respondents_information', 'individual_lifecycle_risks.information_id', '=', 'respondents_information.id')
            ->where('respondents_information.barangay', '=', $barangay)
            ->whereNull('respondents_information.deleted_at')
            ->get();


        $er = DB::table('economic_risks')

            ->select(
                // 1
                DB::raw('sum(no_work=1) as e31a'),
                DB::raw('sum(no_work=2) as e31b'),
                DB::raw('sum(no_work=3) as e31c'),

                DB::raw('sum(work_notalign_to_profession=1) as e32a'),
                DB::raw('sum(work_notalign_to_profession=2) as e32b'),
                DB::raw('sum(work_notalign_to_profession=3) as e32c'),

                DB::raw('sum(experienced_workaccident=1) as e33a'),
                DB::raw('sum(experienced_workaccident=2) as e33b'),
                DB::raw('sum(experienced_workaccident=3) as e33c'),

                DB::raw('sum(unpaid_loans=1) as e34a'),
                DB::raw('sum(unpaid_loans=2) as e34b'),
                DB::raw('sum(unpaid_loans=3) as e34c'),

                DB::raw('sum(victim_of_scamming=1) as e35a'),
                DB::raw('sum(victim_of_scamming=2) as e35b'),
                DB::raw('sum(victim_of_scamming=3) as e35c'),

                DB::raw('sum(victim_of_illegalrecruitment=1) as e36a'),
                DB::raw('sum(victim_of_illegalrecruitment=2) as e36b'),
                DB::raw('sum(victim_of_illegalrecruitment=3) as e36c'),

                DB::raw('sum(change_of_cityaddress=1) as e37a'),
                DB::raw('sum(change_of_cityaddress=2) as e37b'),
                DB::raw('sum(change_of_cityaddress=3) as e37c'),

                DB::raw('sum(no_lifeinsurance=1) as e38a'),
                DB::raw('sum(no_lifeinsurance=2) as e38b'),
                DB::raw('sum(no_lifeinsurance=3) as e38c'),

                DB::raw('sum(no_healthinsurance=1) as e39a'),
                DB::raw('sum(no_healthinsurance=2) as e39b'),
                DB::raw('sum(no_healthinsurance=3) as e39c'),

                DB::raw('sum(working_without_sss_or_gsis=1) as e40a'),
                DB::raw('sum(working_without_sss_or_gsis=2) as e40b'),
                DB::raw('sum(working_without_sss_or_gsis=3) as e40c'),


                DB::raw('sum(bankrupt_in_business=1) as e41a'),
                DB::raw('sum(bankrupt_in_business=2) as e41b'),
                DB::raw('sum(bankrupt_in_business=3) as e41c'),

                DB::raw('sum(mortgage_of_posessions=1) as e42a'),
                DB::raw('sum(mortgage_of_posessions=2) as e42b'),
                DB::raw('sum(mortgage_of_posessions=3) as e42c'),

                DB::raw('sum(loaned_money_from_relatives=1) as e43a'),
                DB::raw('sum(loaned_money_from_relatives=2) as e43b'),
                DB::raw('sum(loaned_money_from_relatives=3) as e43c'),

                DB::raw('sum(family_elder_without_pension=1) as e44a'),
                DB::raw('sum(family_elder_without_pension=2) as e44b'),
                DB::raw('sum(family_elder_without_pension=3) as e44c'),

            )
            ->join('respondents_information', 'economic_risks.information_id', '=', 'respondents_information.id')
            ->where('respondents_information.barangay', '=', $barangay)
            ->whereNull('respondents_information.deleted_at')
            ->get();

        $edr = DB::table('environment_and_disaster_risks')

            ->select(
                // 45
                DB::raw('sum(victim_of_housefire=1) as edr45a'),
                DB::raw('sum(victim_of_housefire=2) as edr45b'),
                DB::raw('sum(victim_of_housefire=3) as edr45c'),

                DB::raw('sum(victim_of_typoon_drought=1) as edr46a'),
                DB::raw('sum(victim_of_typoon_drought=2) as edr46b'),
                DB::raw('sum(victim_of_typoon_drought=3) as edr46c'),

                DB::raw('sum(victim_of_earthquake_volcanic_eruption=1) as edr47a'),
                DB::raw('sum(victim_of_earthquake_volcanic_eruption=2) as edr47b'),
                DB::raw('sum(victim_of_earthquake_volcanic_eruption=3) as edr47c'),

                DB::raw('sum(death_of_family_via_calamity=1) as edr48a'),
                DB::raw('sum(death_of_family_via_calamity=2) as edr48b'),
                DB::raw('sum(death_of_family_via_calamity=3) as edr48c'),

                DB::raw('sum(house_demolition=1) as edr49a'),
                DB::raw('sum(house_demolition=2) as edr49b'),
                DB::raw('sum(house_demolition=3) as edr49c'),

                DB::raw('sum(resided_in_relocation_resettlement=1) as edr50a'),
                DB::raw('sum(resided_in_relocation_resettlement=2) as edr50b'),
                DB::raw('sum(resided_in_relocation_resettlement=3) as edr50c'),

            )
            ->join('respondents_information', 'environment_and_disaster_risks.information_id', '=', 'respondents_information.id')
            ->where('respondents_information.barangay', '=', $barangay)
            ->whereNull('respondents_information.deleted_at')
            ->get();

        $sgr = DB::table('social_and_governance_risks')

            ->select(
                // 51
                DB::raw('sum(victim_of_burglary=1) as s51a'),
                DB::raw('sum(victim_of_burglary=2) as s51b'),
                DB::raw('sum(victim_of_burglary=3) as s51c'),

                DB::raw('sum(experienced_buglary_holdup=1) as s52a'),
                DB::raw('sum(experienced_buglary_holdup=2) as s52b'),
                DB::raw('sum(experienced_buglary_holdup=3) as s52c'),

                DB::raw('sum(experienced_sexual_assult=1) as s53a'),
                DB::raw('sum(experienced_sexual_assult=2) as s53b'),
                DB::raw('sum(experienced_sexual_assult=3) as s53c'),

                DB::raw('sum(qna_knowledge_with_youth_gangs=1) as s54a'),
                DB::raw('sum(qna_knowledge_with_youth_gangs=2) as s54b'),
                DB::raw('sum(qna_knowledge_with_youth_gangs=3) as s54c'),

                DB::raw('sum(death_of_family_due_to_crime=1) as s55a'),
                DB::raw('sum(death_of_family_due_to_crime=2) as s55b'),
                DB::raw('sum(death_of_family_due_to_crime=3) as s55c'),

                DB::raw('sum(witnessed_actual_crime_in_comminity=1) as s56a'),
                DB::raw('sum(witnessed_actual_crime_in_comminity=2) as s56b'),
                DB::raw('sum(witnessed_actual_crime_in_comminity=3) as s56c'),

                DB::raw('sum(election_violence=1) as s57a'),
                DB::raw('sum(election_violence=2) as s57b'),
                DB::raw('sum(election_violence=3) as s57c'),

                DB::raw('sum(fear_of_disturbance_dueto_election=1) as s58a'),
                DB::raw('sum(fear_of_disturbance_dueto_election=2) as s58b'),
                DB::raw('sum(fear_of_disturbance_dueto_election=3) as s58c'),

                DB::raw('sum(rebellion_against_government=1) as s59a'),
                DB::raw('sum(rebellion_against_government=2) as s59b'),
                DB::raw('sum(rebellion_against_government=3) as s59c'),

                DB::raw('sum(rebellion_of_military=1) as s60a'),
                DB::raw('sum(rebellion_of_military=2) as s60b'),
                DB::raw('sum(rebellion_of_military=3) as s60c'),

                // 61

                DB::raw('sum(violence_at_home=1) as s61a'),
                DB::raw('sum(violence_at_home=2) as s61b'),
                DB::raw('sum(violence_at_home=3) as s61c'),

                DB::raw('sum(violence_to_minor=1) as s62a'),
                DB::raw('sum(violence_to_minor=2) as s62b'),
                DB::raw('sum(violence_to_minor=3) as s62c'),

                DB::raw('sum(child_labor=1) as s63a'),
                DB::raw('sum(child_labor=2) as s63b'),
                DB::raw('sum(child_labor=3) as s63c'),

                DB::raw('sum(corruption_in_government=1) as s64a'),
                DB::raw('sum(corruption_in_government=2) as s64b'),
                DB::raw('sum(corruption_in_government=3) as s64c'),

                DB::raw('sum(nonparticipant_in_comunity_activities=1) as s65a'),
                DB::raw('sum(nonparticipant_in_comunity_activities=2) as s65b'),
                DB::raw('sum(nonparticipant_in_comunity_activities=3) as s65c'),

                DB::raw('sum(nonvoter=1) as s66a'),
                DB::raw('sum(nonvoter=2) as s66b'),
                DB::raw('sum(nonvoter=3) as s66c'),

                DB::raw('sum(non_membership_inany_organization=1) as s67a'),
                DB::raw('sum(non_membership_inany_organization=2) as s67b'),
                DB::raw('sum(non_membership_inany_organization=3) as s67c'),

                DB::raw('sum(claim_land_of_others=1) as s68a'),
                DB::raw('sum(claim_land_of_others=2) as s68b'),
                DB::raw('sum(claim_land_of_others=3) as s68c'),

                DB::raw('sum(harmful_tradition_to_people=1) as s69a'),
                DB::raw('sum(harmful_tradition_to_people=2) as s69b'),
                DB::raw('sum(harmful_tradition_to_people=3) as s69c'),

                DB::raw('sum(religious_misunderstanding_dispute=1) as s70a'),
                DB::raw('sum(religious_misunderstanding_dispute=2) as s70b'),
                DB::raw('sum(religious_misunderstanding_dispute=3) as s70c'),


                // last 4
                DB::raw('sum(experienced_slow_justicesystem=1) as s71a'),
                DB::raw('sum(experienced_slow_justicesystem=2) as s71b'),
                DB::raw('sum(experienced_slow_justicesystem=3) as s71c'),

                DB::raw('sum(discrimination_in_ethnicity_gender=1) as s72a'),
                DB::raw('sum(discrimination_in_ethnicity_gender=2) as s72b'),
                DB::raw('sum(discrimination_in_ethnicity_gender=3) as s72c'),

                DB::raw('sum(tribal_dispute=1) as s73a'),
                DB::raw('sum(tribal_dispute=2) as s73b'),
                DB::raw('sum(tribal_dispute=3) as s73c'),

                DB::raw('sum(other_harms_to_family=1) as s74a'),
                DB::raw('sum(other_harms_to_family=2) as s74b'),
                DB::raw('sum(other_harms_to_family=3) as s74c')

            )
            ->join('respondents_information', 'social_and_governance_risks.information_id', '=', 'respondents_information.id')
            ->where('respondents_information.barangay', '=', $barangay)
            ->whereNull('respondents_information.deleted_at')
            ->get();


        $data = [
            'title' => 'FAMILY SURVEY ON RISKS AND VULNERABILITY ',
            'barangay' => $barangay,
            'date' => date('m/d/Y'),
            'ilr' => $ilr,
            'er' => $er,
            'edr' => $edr,
            'sgr' => $sgr,

        ];

        $pdf = PDF::loadView('survey', $data);
        return $pdf->stream();
    }
}
