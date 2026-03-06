# PHP_Laravel12_Process_Approval

A simple multi-step approval workflow system built with Laravel 12.


## Project Description

This project is a simple Process Approval System built using Laravel 12.
Users can submit approval requests, and assigned approvers can review and approve or reject them.
The system tracks approval status and keeps a history of all approval actions.



## Project Features

- User authentication (Login & Register)
- Users can create approval requests
- Each request contains title and description
- Multi-step approval workflow
- Approvers can approve or reject requests
- Comments can be added during approval
- Approval history tracking
- Dashboard for request overview
- Status tracking (Pending / Approved / Rejected)



## Technologies Used

1. Laravel 12 – PHP Framework

2. PHP – Backend Programming Language

3. MySQL – Database

4. Blade – Laravel Template Engine

5. HTML5 – Structure of pages

6. CSS3 – Styling and UI

7. Laravel Breeze – Authentication system

8. Composer – PHP Dependency Manager

9. Node.js & NPM – Frontend asset management



## Project Workflow

- User logs into the system.

- User creates a new approval request.

- The system assigns the request to an approver.

- The approver reviews the request.

- Approver approves or rejects with comments.

- The request status updates and history is recorded.


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Process_Approval "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Process_Approval

```

#### Explanation:

This step creates a new Laravel 12 application using Composer and moves into the project folder to start development.





## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_process_approval
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_process_approval

```

### Run migrations:

```
php artisan migrate

```


#### Explanation:

Configure Laravel to connect with MySQL by updating the .env file and creating the database in phpMyAdmin.

Then run migrations to create default Laravel tables.





## STEP 3: Create Models & Migrations

### Run:

```
php artisan make:model ApprovalRequest -m

php artisan make:model ApprovalFlow -m

php artisan make:model ApprovalHistory -m

```


### Open: database/migrations/xxxx_create_approval_requests_table.php:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
    }
};


```



### Open: database/migrations/xxxx_create_approval_flows_table.php:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('approval_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');   // ADD THIS
            $table->string('name');
            $table->unsignedBigInteger('approver_id');
            $table->integer('step');
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('approval_requests')->cascadeOnDelete();
            $table->foreign('approver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_flows');
    }
};


```


### Open: database/migrations/xxxx_create_approval_histories_table.php:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('approver_id');
            $table->string('status'); // approved, rejected
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('approval_requests');
            $table->foreign('approver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_histories');
    }
};


```


### Run migrations:

```
php artisan migrate

```

#### Explanation:

Models represent database tables in Laravel, and migrations define the structure of those tables. 

Here we create tables for approval requests, approval flow, and approval history.



## STEP 4: Define Relationships

### app/Models/User.php

```
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Requests created by this user
    public function approvalRequests()
    {
        return $this->hasMany(ApprovalRequest::class, 'user_id');
    }

    // Approval steps assigned to this user
    public function approvalFlows()
    {
        return $this->hasMany(ApprovalFlow::class, 'approver_id');
    }

    // Approvals this user has acted on
    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class, 'approver_id');
    }
}


```



### app/Models/ApprovalRequest.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status', // pending / approved / rejected
    ];

    // The user who submitted this request
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // All approval actions for this request
    public function histories()
    {
        return $this->hasMany(ApprovalHistory::class, 'request_id');
    }
}

```




### app/Models/ApprovalFlow.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalFlow extends Model
{
    protected $fillable = [
        'request_id',
        'name',
        'approver_id',
        'step'
    ];

    // Relationship with ApprovalRequest
    public function request()
    {
        return $this->belongsTo(ApprovalRequest::class, 'request_id');
    }


}

```



### app/Models/ApprovalHistory.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'approver_id',
        'status',   // approved / rejected
        'comment',  // optional comment
    ];

    // The request this history belongs to
    public function request()
    {
        return $this->belongsTo(ApprovalRequest::class, 'request_id');
    }

    // The user who approved/rejected
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}

```

#### Explanation:

Relationships connect models together (like User → ApprovalRequest). 

This allows Laravel to easily retrieve related data using Eloquent ORM.




## STEP 5: Create Controllers

### Run:

```
php artisan make:controller ApprovalController

php artisan make:controller ApproverController

php artisan make:controller DashboardController


```


### File Open: app/Http/Controllers/ApprovalController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalRequest;
use App\Models\ApprovalFlow;

class ApprovalController extends Controller
{
    public function index()
    {
        $requests = ApprovalRequest::with('user')->get();
        return view('approvals.index', compact('requests'));
    }

    public function create()
    {
        return view('approvals.create');
    }

    public function store(Request $request)
    {
        $approval = ApprovalRequest::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        ApprovalFlow::create([
            'request_id' => $approval->id,
            'name' => 'Manager Approval',
            'approver_id' => 1,
            'step' => 1
        ]);

        return redirect()->route('approvals.index')
            ->with('success', 'Request submitted successfully');
    }
}

```


### File Open: app/Http/Controllers/ApproverController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalFlow;
use App\Models\ApprovalRequest;
use App\Models\ApprovalHistory;

class ApproverController extends Controller
{
    public function pending()
    {
        $flows = ApprovalFlow::where('approver_id', auth()->id())
            ->whereHas('request', fn($q) => $q->where('status', 'pending'))
            ->get();

        return view('approvals.pending', compact('flows'));
    }

    public function action(Request $request, $id)
    {
        $flow = ApprovalFlow::findOrFail($id);

        ApprovalHistory::create([
            'request_id' => $flow->request_id,
            'approver_id' => auth()->id(),
            'status' => $request->status,
            'comment' => $request->comment,
        ]);

        $approvalRequest = ApprovalRequest::findOrFail($flow->request_id);
        $approvalRequest->status = $request->status;
        $approvalRequest->save();

        return redirect()->route('approvals.pending')->with('success', 'Action submitted!');
    }
}

```

### File Open: app/Http/Controllers/DashboardController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalRequest;
use App\Models\ApprovalFlow;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Requests submitted by logged-in user
        $myRequests = ApprovalRequest::where('user_id', $userId)->get();

        // Pending approvals for logged-in user
        $pendingApprovals = ApprovalFlow::where('approver_id', $userId)->get();

        return view('dashboard', compact('myRequests', 'pendingApprovals'));
    }
}

```

#### Explanation:

Controllers handle the application logic such as creating requests, approving or rejecting them, and displaying dashboard data.





## STEP 6: Define Routes

### routes/web.php:

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', function () {
    return view('welcome');
});


// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');



/*
|--------------------------------------------------------------------------
| Approval System Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |------------------------------------------------
    | User Request Routes
    |------------------------------------------------
    */

    // Create Request Form
    Route::get('/approvals/create', [ApprovalController::class, 'create'])
        ->name('approvals.create');

    // Store Request
    Route::post('/approvals/store', [ApprovalController::class, 'store'])
        ->name('approvals.store');

    // View All Requests (My Requests)
    Route::get('/approvals', [ApprovalController::class, 'index'])
        ->name('approvals.index');


    /*
    |------------------------------------------------
    | Approver Routes
    |------------------------------------------------
    */

    // Pending Approvals
    Route::get('/approvals/pending', [ApproverController::class, 'pending'])
        ->name('approvals.pending');

    // Approve / Reject Action
    Route::post('/approvals/{id}/action', [ApproverController::class, 'action'])
        ->name('approvals.action');


    /*
    |------------------------------------------------
    | Approval History
    |------------------------------------------------
    */

    Route::get('/approvals/history/{id}', [ApproverController::class, 'history'])
        ->name('approvals.history');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

```

#### Explanation:

Routes define the URLs of the application and map them to controller methods so users can access different features like creating requests or approving them.





## STEP 7: Create Views

### resources/views/approvals/create.blade.php

```
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Approval Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #edf2f7;
            padding: 40px;
        }

        h2 {
            color: #1f2937;
            text-align: center;
            margin-bottom: 20px;
        }

        .back-button {
            display: block;
            margin: 0 auto 20px auto;
            text-align: center;
        }

        .back-button a {
            display: inline-block;
            background: #6b7280;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }

        .back-button a:hover {
            background: #4b5563;
        }

        form {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }

        button {
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background: #2563eb;
        }
    </style>
</head>

<body>

    <div class="back-button">
        <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
    </div>

    <h2>Create Approval Request</h2>
    <form action="{{ route('approvals.store') }}" method="POST">
        @csrf
        <input type="text" name="title" placeholder="Request Title" required>
        <textarea name="description" placeholder="Request Description" rows="5" required></textarea>
        <button type="submit">Submit Request</button>
    </form>

</body>

</html>

```


### resources/views/approvals/index.blade.php

```
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Approval Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #edf2f7;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 20px;
        }

        .back-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .back-button a {
            display: inline-block;
            background: #6b7280;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }

        .back-button a:hover {
            background: #4b5563;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #3b82f6;
            color: #fff;
        }

        tr:hover {
            background: #f1f5f9;
        }

        .status-pending {
            color: #f59e0b;
            font-weight: bold;
        }

        .status-approved {
            color: #10b981;
            font-weight: bold;
        }

        .status-rejected {
            color: #ef4444;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="back-button">
        <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
    </div>

    <h2>All Approval Requests</h2>

    <table>
        <tr>
            <th>Title</th>
            <th>User</th>
            <th>Status</th>
        </tr>
        @foreach($requests as $r)
            <tr>
                <td>{{ $r->title }}</td>
                <td>{{ $r->user->name }}</td>
                <td class="status-{{ strtolower($r->status) }}">{{ ucfirst($r->status) }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>

```



### resources/views/approvals/pending.blade.php

```
<!DOCTYPE html>
<html>
<head>
<title>Pending Approvals</title>

<style>

body{
    font-family: Arial, Helvetica, sans-serif;
    background:#f4f6f9;
    padding:40px;
}

h2{
    text-align:center;
    margin-bottom:40px;
}

.card{
    background:white;
    padding:20px;
    margin-bottom:20px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.card h3{
    margin:0 0 10px 0;
}

.card p{
    color:#555;
}

form{
    margin-top:15px;
}

select, textarea{
    width:100%;
    padding:10px;
    margin-top:10px;
    border:1px solid #ddd;
    border-radius:6px;
    font-size:14px;
}

textarea{
    resize:none;
    height:70px;
}

button{
    margin-top:12px;
    padding:10px 18px;
    border:none;
    background:#4CAF50;
    color:white;
    border-radius:6px;
    cursor:pointer;
    font-size:14px;
}

button:hover{
    background:#45a049;
}

.empty{
    text-align:center;
    color:#888;
    font-size:18px;
}

</style>

</head>

<body>

<h2>Pending Approvals</h2>

@forelse($flows as $flow)

<div class="card">

<h3>{{ $flow->request->title }} (Step {{ $flow->step }})</h3>

<p>Request ID: {{ $flow->request_id }}</p>

<form action="{{ route('approvals.action', $flow->id) }}" method="POST">
@csrf

<select name="status">
<option value="approved">Approve</option>
<option value="rejected">Reject</option>
</select>

<textarea name="comment" placeholder="Add comment"></textarea>

<button type="submit">Submit</button>

</form>

</div>

@empty

<p class="empty">No pending approvals.</p>

@endforelse

</body>
</html>

```




### resources/views/dashboard.blade.php

```
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Process Approval Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #edf2f7;
            margin: 0;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 30px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Cards */
        .card {
            background: #fff;
            width: 360px;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #111827;
        }

        .card p {
            margin: 5px 0;
            color: #4b5563;
        }

        /* Badge */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-pending {
            background: #f59e0b;
        }

        .badge-approved {
            background: #10b981;
        }

        .badge-rejected {
            background: #ef4444;
        }

        /* Form Elements */
        form {
            margin-top: 15px;
        }

        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            resize: none;
        }

        button {
            background: #3b82f6;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            transition: background 0.2s;
        }

        button:hover {
            background: #2563eb;
        }

        /* Section Titles */
        .section-title {
            text-align: center;
            font-size: 18px;
            color: #111827;
            margin: 25px 0 10px 0;
            font-weight: bold;
        }

        /* Top Buttons */
        .top-buttons {
            text-align: center;
            margin-bottom: 30px;
        }

        .top-buttons a {
            display: inline-block;
            background: #10b981;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            margin: 0 5px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .top-buttons a:hover {
            background: #059669;
        }
    </style>
</head>

<body>

    <h1>Process Approval Dashboard</h1>

    <div class="top-buttons">
        <a href="{{ route('approvals.create') }}">+ Create Request</a>
    </div>

    <!-- User Submitted Requests -->
    <div class="section-title">My Submitted Requests</div>
    <div class="container">
        @foreach($myRequests as $request)
            <div class="card">
                <h3>{{ $request->title }}</h3>
                <p>{{ $request->description }}</p>
                <p><strong>Status:</strong>
                    <span class="badge badge-{{ strtolower($request->status) }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </p>
            </div>
        @endforeach
    </div>

  
</body>
</html>

```

#### Explanation:

Views are Blade templates that display the user interface such as the dashboard, request form, request list, and pending approvals page.






## STEP 8: Auth Scaffolding

### Install authentication:

```
composer require laravel/breeze --dev
php artisan breeze:install
npm install
npm run dev
php artisan migrate

```

#### Explanation:

Laravel Breeze is installed to add login, register, and authentication features so users must log in before accessing the approval system.





## STEP 9: Run Project

### Run:

```
php artisan serve

```

### Open browser:

```
http://127.0.0.1:8000

```

#### Explanation:

The php artisan serve command starts the Laravel development server so you can open the project in the browser.



## Expected Output

### Register Page:


<img width="1919" height="938" alt="Screenshot 2026-03-06 154351" src="https://github.com/user-attachments/assets/65c21258-12c2-4d87-9f77-fe7b3305de64" />


### Dashboard Page


<img width="1915" height="944" alt="Screenshot 2026-03-06 165446" src="https://github.com/user-attachments/assets/e9b4fa09-6c57-4bfc-8ac4-f810d26db74d" />


### Create Approval Request Page


<img width="1897" height="894" alt="Screenshot 2026-03-06 165508" src="https://github.com/user-attachments/assets/344fbb29-91f7-49f5-93f1-5724aaad8955" />


### All Approval Requests Page


<img width="1919" height="904" alt="Screenshot 2026-03-06 170140" src="https://github.com/user-attachments/assets/aab8c7b2-ec5a-40bd-b5b1-85dd0ebf95a0" />


### Pending Approvals Page


<img width="1916" height="929" alt="Screenshot 2026-03-06 170456" src="https://github.com/user-attachments/assets/10c21676-b027-4690-b189-d580af149b69" />


### 6. Approval Action (Approve / Reject)


<img width="1918" height="918" alt="Screenshot 2026-03-06 170616" src="https://github.com/user-attachments/assets/75fa0d46-5970-46c7-b3fd-b1322e29ce46" />




---

# Project Folder Structure:

```
PHP_Laravel12_Process_Approval
│
├── app
│   │
│   ├── Http
│   │   └── Controllers
│   │       ├── ApprovalController.php
│   │       ├── ApproverController.php
│   │       └── DashboardController.php
│   │
│   └── Models
│       ├── ApprovalFlow.php
│       ├── ApprovalHistory.php
│       ├── ApprovalRequest.php
│       └── User.php
│
│
├── bootstrap
│   └── app.php
│
│
├── config
│   ├── app.php
│   ├── auth.php
│   └── database.php
│
│
├── database
│   │
│   ├── factories
│   │
│   ├── migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── xxxx_create_approval_requests_table.php
│   │   ├── xxxx_create_approval_flows_table.php
│   │   └── xxxx_create_approval_histories_table.php
│   │
│   └── seeders
│       └── DatabaseSeeder.php
│
│
├── public
│   └── index.php
│
│
├── resources
│   │
│   ├── css
│   │
│   ├── js
│   │
│   └── views
│       │
│       ├── approvals
│       │   ├── create.blade.php
│       │   ├── index.blade.php
│       │   └── pending.blade.php
│       │
│       ├── dashboard.blade.php
│       ├── welcome.blade.php
│       │
│       └── auth
│           ├── login.blade.php
│           ├── register.blade.php
│           └── forgot-password.blade.php
│
│
├── routes
│   ├── web.php
│   └── auth.php
│
│
├── storage
│
│
├── vendor
│
│
├── .env
├── artisan
├── composer.json
└── package.json

```
