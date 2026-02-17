<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RespondController;
use App\Http\Controllers\TenderController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

// Public participant response
Route::get('/respond/{token}', [RespondController::class, 'show'])->name('respond.show');
Route::post('/respond/{token}', [RespondController::class, 'save'])->name('respond.save');
Route::post('/respond/{token}/submit', [RespondController::class, 'submit'])->name('respond.submit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Tenders
    Route::resource('tenders', TenderController::class);
    Route::post('/tenders/{tender}/activate', [TenderController::class, 'activate'])->name('tenders.activate');
    Route::post('/tenders/{tender}/review', [TenderController::class, 'review'])->name('tenders.review');
    Route::post('/tenders/{tender}/complete', [TenderController::class, 'complete'])->name('tenders.complete');

    // Criteria
    Route::post('/tenders/{tender}/criteria', [CriterionController::class, 'store'])->name('criteria.store');
    Route::put('/criteria/{criterion}', [CriterionController::class, 'update'])->name('criteria.update');
    Route::delete('/criteria/{criterion}', [CriterionController::class, 'destroy'])->name('criteria.destroy');

    // Questions
    Route::post('/criteria/{criterion}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::post('/criteria/{criterion}/generate', [QuestionController::class, 'generate'])->name('questions.generate');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Participants
    Route::post('/tenders/{tender}/participants', [ParticipantController::class, 'store'])->name('participants.store');
    Route::delete('/participants/{participant}', [ParticipantController::class, 'destroy'])->name('participants.destroy');
    Route::post('/participants/{participant}/resend', [ParticipantController::class, 'resend'])->name('participants.resend');
    Route::post('/participants/{participant}/lock', [ParticipantController::class, 'lock'])->name('participants.lock');

    // Documents
    Route::post('/tenders/{tender}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Analysis
    Route::get('/tenders/{tender}/analysis', [AnalysisController::class, 'show'])->name('analysis.show');
    Route::post('/tenders/{tender}/analysis', [AnalysisController::class, 'generate'])->name('analysis.generate');
    Route::get('/tenders/{tender}/session-prep', [AnalysisController::class, 'sessionPrep'])->name('analysis.session-prep');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
