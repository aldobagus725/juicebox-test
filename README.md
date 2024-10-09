# JUICEBOX-TEST
<h3>A Laravel Code Test - JuiceBox</h3>
<h5>SETUP Instructions</h5>
<hr />
<ol>
    <li>You need to at least have:
        <ul>
            <li>PHP 8.2 installed</li>
            <li>MySQL DB installed</li>
            <li>Composer installed (download link for windows: https://getcomposer.org/Composer-Setup.exe , for Mac & Linux, please follow the guidelines from the Composer Documentation!)</li>
            <li>Postman installed (for API testing)</li>
            <li>For dev purpose, you can use XAMPP to uphold the requirement.</li>
        </ul>
    </li>
    <li>Download this repository, then extract it to your preferred directory.</li>
    <li>Run cmd or terminal on the repo's directory</li>
    <li>Run command : <code>composer install </code></li>
    <li>Duplicate .env.example to .env, and adjust your DB configuration (host, username, password, DB name)</li>
    <li>Run <code>php artisan migrate:fresh --seed --seeder=PermissionSeeder</code> to run table migration and insert permission</li>
    <li>Run <code>php artisan server</code> to server the backend's API.</li>
</ol>