<?php

use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

// Home > Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Dashboard', route('dashboard'));
});

// Home > Dashboard > Users
Breadcrumbs::for('user.list', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('User', route('user.list'));
});

// Home > Dashboard > Users > Add User
Breadcrumbs::for('user.add', function (BreadcrumbTrail $trail) {
    $trail->parent('user.list');
    $trail->push('Add User', route('user.add'));
});

// Home > Dashboard > Users > Edit User
Breadcrumbs::for('user.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('user.list');
    $trail->push('Edit User', route('user.edit','user_id'));
});


// Home > Dashboard > Setting
Breadcrumbs::for('program.list', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Program', route('program.list'));
});


// Home > Dashboard > Workshop > Add Workshop
Breadcrumbs::for('program.add', function (BreadcrumbTrail $trail) {
    $trail->parent('program.list');
    $trail->push('Add Program', route('program.add','program_id'));
});

// Home > Dashboard > Registration
Breadcrumbs::for('student_registration.list', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Student Registration', route('student_registration.list'));
});


// Home > Dashboard > Registration > Edit Registration
Breadcrumbs::for('student_registration.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('student_registration.list');
    $trail->push('Edit Student Registration', route('student_registration.edit','student_registration_id'));
});


