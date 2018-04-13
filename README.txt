1.Steps to create and initialize the database.
    1.1 Create a database called angel_news OR change it in the config/db.php file.
    1.2 Run migrations

2. Steps to prepare the source code to build/run properly
    2.1 Code should work out of the box. Some changes to the config may be required (db.php )

3. Any assumptions made and missing requirements that are not covered in the specifications
    3.1 I assumed a username should be required since showing people's emails to everyone is a bad choice
    3.2 I skipped over the is_validated requirement because it makes no sense to allow people to login without a password. The password itself is created upon validation
    3.3 I skipped over the RSS feed and the PDF download requirements.
    3.4 I added a pagination so you can view all of the news ever published because just displaying the last 10 seemed like terrible design. Now it's 10 per page
    3.5 Only added unit tests for the domain models

4. Any feedback you may wish to give about improving the assignment
    4.1 The task is waaaaaaaaaaaaaaaaaaaaaaaaaay too big
    4.2 Too much extra info required (readme, design diagrams which I have decided not to bother with)