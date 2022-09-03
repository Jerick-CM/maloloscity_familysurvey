business-itinerary.maloloscity.gov.ph


reference: https://laraveldaily.com/laravel-8-vue-3-crud-composition-api/


npm install && npm run dev

https://techvblogs.com/blog/laravel-import-export-excel-csv-file

create table for business
create sample company migration table.


php artisan make:migration create_companies_table

php artisan route:list

installed vue js plugin component.


install datatable for vue3

npm install --save @jobinsjp/vue3-datatable

create modal , create factory and seeder for company.
php artisan make:factory CompanyFactory

php artisan make:factory CompanyFactory --model=Company
php artisan db:seed --class=CompanySeeder


php artisan make:factory UserFactory --model=User


php artisan make:seeder UserSeeder



php artisan db:seed --class=UserSeeder


php artisan make:migration create_business_table

php artisan make:seeder BusinessSeeder

php artisan make:factory BusinessFactory --model=Business

php artisan db:seed --class=BusinessSeeder

php artisan make:resource Business --collection

implement baranggay and minicipality migration


php artisan make:migration create_barangay_table


php artisan make:seeder BarangaySeeder


php artisan db:seed --class=BarangaySeeder


php artisan make:seeder MunicipalitySeeder

php artisan make:migration create_municipilty_table


add autoincrement to an existing table
ALTER TABLE `municipalities` ADD `id` INT NOT NULL AUTO_INCREMENT;


run seeder class for municipalitiesMunicipalitySeeder


php artisan db:seed --class=MunicipalitySeeder


done implementing migration for barangay and municipalities places.


php artisan make:migration add_newfields_to_business --table="business"

add new fields to business
unit house number, baranggay and minicipality i think.


php artisan make:controller Locationontroller --resource

php artisan make:migration add_newfields_strategiclocation_to_business --table="business"


delete from yourTableName where yourColumnName=' ' OR yourColumnName IS NULL;

delete from business where B<>'' AND D IS NULL;
-- select * From business  where B<>'' AND D IS NULL;


ALTER TABLE `business` ADD primary key `id` INT NOT NULL AUTO_INCREMENT  primary key ;


ALTER TABLE `business` ADD COLUMN `id` INT AUTO_INCREMENT UNIQUE FIRST;


php artisan make:migration create_businesses_table

php artisan make:migration create_lineofbusiness_table

done table data migration of masterlist finally 1:16 thursday

create line of business category and resouce


adding multi select plugin
https://jsfiddle.net/t421d7cg/


create the itinerary and pdf download.


1. implement business search selection
2. then create a table saving for the created itinerary
3. create a pdf printing of created itinerary.


php artisan make:migration create_itinerary_table -mrc

php artisan make:model \Models\Itinerary -mcr\
generate pdf done, implement change title done.

to implement date period of itinerary per day and inclusivity


https://www.tutsmake.com/laravel-9-generator-qr-code-tutorial-with-example/


https://ourcodeworld.com/articles/read/349/how-to-install-and-enable-the-imagick-extension-in-xampp-for-windows

remember in installing image magic restart the pc after installation

php artisan storage:link


Toast
toast("Default toast");
toast.info("Info toast");
toast.success("Success toast");
toast.error("Error toast");
toast.warning("Warning toast");


business create add toast, fix , digits , fix error location, on success create clear the fields.

UPDATE `businesses`
SET `strategic_location` = "Robinsons Place"  WHERE `address` LIKE '%Robinsons Place%'

UPDATE `businesses`
SET `strategic_location` = "VISTA MALL"  WHERE `address` LIKE '%VISTA MALL%'


UPDATE `businesses`
SET `strategic_location` = "CABANAS MALL"  WHERE `address` LIKE '%CABANAS MALL%'

UPDATE `businesses`
SET `strategic_location` = "GRACELAND MALL"  WHERE `address` LIKE '%GRACELAND MALL%'

UPDATE `businesses`
        SET `strategic_location` = 'MAUNLAD MALL'  WHERE `address` LIKE '%MAUNLAD MALL%'


        SELECT count(*) FROM `businesses` WHERE strategic_location is null;


        select count(*) from businesses where strategic_location is not  null;
select count(*) from businesses where strategic_location is  null;

php artisan make:controller PhotoController --model=Photo --resource --requests



new requirements

1. create control number  check
2. add baranggay filter
3 reformat itinerary creation
4. add table select field checklist

1 super user , 1 all user,
1 pull itenerary, and add checklists.
ability to add business in itenerary


php artisan make:migration add_checklist_table
php artisan make:migration add_checklist_items

created check list field table.

php artisan make:model Checklist -mcr

php artisan make:seeder ChecklistsSeeder

php artisan db:seed --class=ChecklistsSeeder

php artisan make:model ItineraryBusiness -mcr

SELECT itineraries.id as it_id, itinerary_businesses.status, businesses.*
FROM itineraries
INNER JOIN itinerary_businesses ON itineraries.id=itinerary_businesses.itinerary_id
INNER JOIN businesses ON businesses.id=itinerary_businesses.business_id
WHERE itineraries.id=1;


DB::table('itineraries')
->select('itineraries.id as it_id', 'itinerary_businesses.status', 'businesses.*')
->join('itinerary_businesses','itineraries.id','=','itinerary_businesses.itinerary_id')
->join('businesses','businesses.id','=','itinerary_businesses.business_id')
->where('itineraries.id','=',1)
->get();


 php artisan optimize

 php artisan vendor:publish --tag=laravel-errors


create business filter.


php artisan make:export UsersExport --model=User
php artisan make:export BusinessExport --model=Business
php artisan make:export ItineraryExport --model=Itinerary

php artisan make:controller ExportsController --resource

SELECT * FROM `businesses` WHERE `business_identification_number` = '216-00-2013-0000322';


refresh changes from modifying .env

Run php artisan config:clear

php -S localhost:8000 -t public

implement indexing.

php artisan make:model Role -mcr
php artisan make:model Role_Users -mcr


php artisan db:seed --class=RoleSeeder
php artisan make:seeder RoleSeeder



new

php artisan make:migration respondents_information_table
php artisan make:migration individual_life_cycle_risk_table
php artisan make:migration economic_risk_table
php artisan make:migration environment_and_disaster_risk_table
php artisan make:migration social_and_governance_risk_table


php artisan make:controller RespondentsInformationController --model=RespondentsInformation
php artisan make:controller IndividualLifeCycleRiskController --model=IndividualLifeCycleRisk
php artisan make:controller EconomicRiskController --model=EconomicRisk
php artisan make:controller EnvironmentAndDisasterRiskController --model=EnvironmentAndDisasterRisk
php artisan make:controller SocialAndGovernanceRiskController --model=SocialAndGovernanceRisk
