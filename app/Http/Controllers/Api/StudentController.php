<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Student;
use App\Models\ParentGuardian;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentOnlineEnrollmentResource;

use App\Traits\Messages;
use App\Traits\AddressHelpers;

class StudentController extends Controller
{
    use Messages, AddressHelpers;

    private $http_code_ok;
    private $http_code_error;    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->http_code_ok = 200;
        $this->http_code_error = 500;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'lrn' => 'string',
            'lastname' => 'string',
            'firstname' => 'string',
            'middlename' => 'string',
            // 'ext_name' => 'string',
            'date_of_birth' => 'date',
            // 'place_of_birth' => 'string',
            'gender' => 'string',
            'house_no' => 'string',
            'barangay' => 'string',
            'city' => 'string',
            'province' => 'string',
            'region' => 'string',
            'zip_code' => 'string',
            // 'home_address' => 'string', # house no street name subd, barangay, city, province, zip code, 
            'contact_no' => 'string',
            'email_address' => ['string','email','max:191'],            
            'indigenous' => 'string', 
            'mother_tongue' => 'string',
            'relationship' => 'string', // Parent/Guardian
            'gp_lastname' => 'string',
            'gp_firstname' => 'string',
            // 'gp_middlename' => 'string',
            'gp_contact_no' => 'string',
        ];

        $validator = Validator::make($request->all(), $rules);
		
        if ($validator->fails()) {
            // return $validator->errors();
            return $this->jsonErrorDataValidation();
        }

        /** Get validated data */
        $data = $validator->valid();

        // return $data;

        // Home address
        $barangay = $this->getBarangay($data['barangay']);
        $city = $this->getCity($data['city']);
        $province = $this->getProvince($data['province']);
        $home_address = "{$data['house_no']}, {$barangay}, {$city}, {$province}";
        $data['home_address'] = $home_address;

        $data['origin'] = 'walk-in';

        $student = new Student;
        $student->fill($data);
        $student->save();

        // Parent/Guardian
        $parent = [
            'relationship' => $data['relationship'],
            'last_name' => $data['gp_lastname'],
            'first_name' => $data['gp_firstname'],
            'middle_name' => $data['gp_middlename'],
            'contact_no' => $data['gp_contact_no'],
        ];
        $pg = new ParentGuardian;
        $pg->fill($parent);
        $student->parents()->save($pg);         

        $data = new StudentResource($student);

        return $this->jsonSuccessResponse($data, 200, 'New student successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (filter_var($id, FILTER_VALIDATE_INT) === false ) {
            return $this->jsonErrorInvalidParameters();
        }

        $student = Student::find($id);      

        if (is_null($student)) {
			return $this->jsonErrorResourceNotFound();
        }

        $data = new StudentResource($student);

        return $this->jsonSuccessResponse($data, 200); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enrollOnline(Request $request)
    {
        $rules = [
            'lrn' => 'string',
            'lastname' => 'string',
            'firstname' => 'string',
            'middlename' => 'string',
            // 'ext_name' => 'string',
            'date_of_birth' => 'date',
            // 'place_of_birth' => 'string',
            'gender' => 'string',
            'house_no' => 'string',
            'barangay' => 'string',
            'city' => 'string',
            'province' => 'string',
            'region' => 'string',
            'zip_code' => 'string',
            // 'home_address' => 'string', # house no street name subd, barangay, city, province, zip code, 
            'contact_no' => 'string',
            'email_address' => ['string','email','max:191'],            
            'indigenous' => 'string', 
            'mother_tongue' => 'string',
            'relationship' => 'string', // Parent/Guardian
            'gp_lastname' => 'string',
            'gp_firstname' => 'string',
            // 'gp_middlename' => 'string',
            'gp_contact_no' => 'string',
        ];

        $validator = Validator::make($request->all(), $rules);
		
        if ($validator->fails()) {
            // return $validator->errors();
            return $this->jsonErrorDataValidation();
        }

        /** Get validated data */
        $data = $validator->valid();

        // return $data;

        // Home address
        $barangay = $this->getBarangay($data['barangay']);
        $city = $this->getCity($data['city']);
        $province = $this->getProvince($data['province']);
        $home_address = "{$data['house_no']}, {$barangay}, {$city}, {$province}";
        $data['home_address'] = $home_address;

        $data['origin'] = 'online';

        $student = new Student;
        $student->fill($data);
        $student->save();

        // Parent/Guardian
        $parent = [
            'relationship' => $data['relationship'],
            'last_name' => $data['gp_lastname'],
            'first_name' => $data['gp_firstname'],
            'middle_name' => $data['gp_middlename'],
            'contact_no' => $data['gp_contact_no'],
        ];
        $pg = new ParentGuardian;
        $pg->fill($parent);
        $student->parents()->save($pg);         

        $data = new StudentResource($student);

        return $this->jsonSuccessResponse($data, 200, 'New student successfully added');
    }    

    public function queryByLRNBday(Request $request)
    {
        $rules = [
            'lrn' => 'string',
            'birthday' => 'date'
        ];

        $validator = Validator::make($request->all(), $rules);
		
        if ($validator->fails()) {
            // return $validator->errors();
            return $this->jsonErrorDataValidation();
        }

        $data = $validator->valid();        

        $student = Student::where([['lrn',$data['lrn']],['date_of_birth',$data['birthday']]])->first();

        if (is_null($student)) {
			return $this->jsonErrorResourceNotFound();
        }

        $data = new StudentOnlineEnrollmentResource($student);

        return $this->jsonSuccessResponse($data, 200);        
    }
}
