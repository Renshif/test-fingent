# Invoice Generator

## Requirement

PHP version 7.4 or higher is required

## Installation

### Step 1: Clone the git

Use the following command

<pre>git clone https://github.com/Renshif/test-fingent.git</pre>

### Step 2: Connect to database

Open <code>app > Config > Database.php</code> and update your db credentials

<pre>
'hostname' => 'HOST_NAME',
'username' => 'YOUR_MYSQL_USERNAME',
'password' => 'YOUR_MYSQL_PASSWORD',
'database' => 'YOUR_DATABASE_NAME',
</pre>

### Step 3: Update your project base url

Open <code>app > Config > App.php</code> and replace value of $baseURL with <code>http://localhost/FOLDER_NAME</code>

### Step 3: Import database

Import the sql file in root named <code>invoice_generator.sql</code> to your database

### Step 4: Run the application

Run the application on <code>localhost/FOLDER_NAME/</code>
