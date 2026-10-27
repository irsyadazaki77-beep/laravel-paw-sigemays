<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $projectData = [
            'appName' => 'Si Gemays Portal',
            'course' => 'Praktikum Pengembangan Aplikasi Web (PAW) - Modul 6',
            'university' => 'Telkom University Surabaya',
            'teamMembers' => [
                [
                    'name' => 'Zaki Irsyada Putra',
                    'role' => 'Mahasiswa A (Project Lead & Home Portal)',
                    'branch' => 'zaki',
                    'status' => 'Lead'
                ],
                [
                    'name' => 'Demyan',
                    'role' => 'Mahasiswa B (Data CRUD Module)',
                    'branch' => 'demyan',
                    'status' => 'Member'
                ],
                [
                    'name' => 'Daniel H',
                    'role' => 'Mahasiswa C (Auth & User Profile)',
                    'branch' => 'daniel',
                    'status' => 'Member'
                ],
                [
                    'name' => 'Mahasiswa D',
                    'role' => 'Mahasiswa D (Reports & Feedback Module)',
                    'branch' => 'mahasiswa-d',
                    'status' => 'Member'
                ]
            ]
        ];

        return view('welcome', compact('projectData'));
    }
}
