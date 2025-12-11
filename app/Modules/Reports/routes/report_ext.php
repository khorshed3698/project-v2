<?php
Route::post('/reports/show-crystal-report', "PdfReportController@showCrystalReportModal");
Route::post('/reports/show-crystal-report-data', "PdfReportController@showCrystalReportData");
Route::post('/reports/generate-crystal-report', "PdfReportController@generateCrystalReport");
Route::post('/reports/ajax-crystal-report-feedback', "PdfReportController@ajaxApiFeedback");
Route::post('/reports/update-download-panel', "PdfReportController@updateDownloadPanel");
