# parking-lot

-   Requirements

1. PHP 7.3
2. Composer
3. MySql

-   Steps to Run

1. clone or download the repo
2. composer install
3. create .env file in root directory and paste the attach .env content in this file
4. create parking_lot database in mysql
5. import parking_lot.sql file present in database/migrations

# Business Logic

Booking

-   First will check user is differently abled or pregnant women (only female), then will check if reserved parking is available then allot in reserve parking else general parking, create an entry in user_parkings with flags has_booked to true(1) else return No slot available
-   For general users check if general parking is available then book the general slot, create an entry in user_parkings with flags has_booked to true(1) else return No slot available

Reached Parking

-   In user_parkings will update has_reached flag to true(1) and reached_at time

Exit Booking

-   In user_parkings will update has_exited flag to true(1) and exited_at time an update is_occupied to false according to parking_id

Cron Job

-   Every minute cron will run to check has_reached(0) and has_lapsed(0) flag, if both is false then compare current_time with created_at = current_date
-   if booking has reached 50% then check for 15 mins lapse else for 30 mins is lapse. Then in parkings table will update is_occupied to false(0) and in user_parkings will update has_lapsed to true(1)

Further added features

-   In parkings price can be different based on size of vehicle (size) can be S,M,L,XL
-   Users can have multiple vehicles so we can create a new table user_vehicles with all vehicles mapping and then used in user_parkings
-   At booking time if existing vehicle then select else insert in user_vehicles
-   There can be multiple levels of parkings so in parkings table will add new column 'floor'
