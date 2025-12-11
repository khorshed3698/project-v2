<?php

use App\Libraries\CommonFunction;
use App\User;
use Carbon\Carbon;

class CommonFunctionTest extends TestCase
{

    public function testShowAuditLogCreatedWithValidInputs()
    {
        // Arrage
        $created_at = '2022-01-01 00:00:00';
        $created_by = 1;

        
        $expected_result = '<span class="help-block">Created : <i>' . Carbon::createFromFormat('Y-m-d H:i:s', $created_at)->diffForHumans() 
        . '</i> by <b>' . User::where('id', $created_by)->first()->user_full_name . '</b></span>';

        $this->assertEquals($expected_result, CommonFunction::showAuditLogCreated($created_at, $created_by));
    }


    public function testShowAuditLogCreatedWithInvalidInputs()
    {
        $created_at = '';
        $created_by = '';

        $expected_result = '<span class="help-block">Created : <i>Unknown</i> by <b>Unknown</b></span>';

        $this->assertEquals($expected_result, CommonFunction::showAuditLogCreated($created_at, $created_by));
    }
    

    public function testShowAuditLogupdatedWithValidInputs()
    {
        $updated_at = '2022-01-01 00:00:00';
        $updated_by = 1;

        $expected_result = '<span class="help-block">Last updated : <i>' . Carbon::createFromFormat('Y-m-d H:i:s', $updated_at)->diffForHumans() 
        . '</i> by <b>' . User::where('id', $updated_by)->first()->user_full_name . '</b></span>';

        $this->assertEquals($expected_result, CommonFunction::showAuditLogupdated($updated_at, $updated_by));
    }


    public function testShowAuditLogupdatedWithInvalidInputs()
    {
        $updated_at = '';
        $updated_by = '';

        $expected_result = '<span class="help-block">Last updated : <i>Unknown</i> by <b>Unknown</b></span>';

        $this->assertEquals($expected_result, CommonFunction::showAuditLogupdated($updated_at, $updated_by));
    }

    
    public function testChangeDateFormat()
    {
        $datePicker = '2022-02-14';
        $expectedResult = '14-Feb-2022';

        $this->assertEquals($expectedResult, CommonFunction::changeDateFormat($datePicker));
        $this->assertEquals($datePicker, CommonFunction::changeDateFormat($expectedResult, true));
        $this->assertEquals($expectedResult, CommonFunction::changeDateFormat('2022-02-14 12:34:56', true, true));
    }


    public function testTrainingAdmin()
    {
        $expected = ['2x201', '2x202', '2x203'];
        $result = CommonFunction::trainingAdmin();
        $this->assertEquals($expected, $result);
    }


    public function testAgencyUser()
    {
        $agencyUser = CommonFunction::agencyUser();

        $this->assertInternalType('array', $agencyUser);
        $this->assertContains('12x431', $agencyUser);
        $this->assertContains('12x432', $agencyUser);
    }


    public function testConvert2Bangla()
    {
        $eng_number = '1234567890';
        $expected = '১২৩৪৫৬৭৮৯০';

        $result = CommonFunction::convert2Bangla($eng_number);

        $this->assertEquals($expected, $result);
    }


    public function testFormateDate()
    {
        // Arrange
        $date = '2022-03-15';

        // Act
        $formattedDate = CommonFunction::formateDate($date);

        // Assert
        $this->assertEquals('15.03.2022', $formattedDate);
    }

}
