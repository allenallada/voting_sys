<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voter;

class VotersController extends Controller
{

    public function create(Request $request)
    {
    	$pieces = explode(" ", $request->input('qr_string'));
    	
    	foreach (array_keys($pieces, "", true) as $key) {
		    unset($pieces[$key]);
		}

		array_pop($pieces);

		$newArray = [];

		foreach ($pieces as $value) {
			array_push($newArray, $value);
		}

		$student_code = array_pop($newArray);

		$voterCheck = Voter::where('student_code', $student_code)->get();

		$count = $voterCheck->count();

		if($count > 0){
			return [
				'error' => [
					'message' => 'already exists'
				]
			];
		}

		$name = implode(" ", $newArray);

    	Voter::create([
    		'qr_string' => request('qr_string'),
    		'name' => $name,
    		'student_code' => $student_code,
    	]);

    	return [
				'success' => [
					'message' => 'successfully registered'
				]
			];
    }

    public function show(Request $request)
    {
    	dd($request->all());
    	return 'connected';
    }

    public function checkIfVoted(Request $request)
    {
    	// dd($request->all());
    	$pieces = explode(" ", $request->input('qr_string'));
    	
    	foreach (array_keys($pieces, "", true) as $key) {
		    unset($pieces[$key]);
		}

		array_pop($pieces);

		$newArray = [];

		foreach ($pieces as $value) {
			array_push($newArray, $value);
		}

		$student_code = array_pop($newArray);

		$voter = Voter::where('student_code', '=' , $student_code)->first();

		if($voter === null) {
			return [
				'error' => [
					'no record'
				]
			];
		}

		$hasVoted = $voter->has_voted;

		return [
			'has_voted' => $hasVoted
		];
    	// return 'connected';
    }
}
