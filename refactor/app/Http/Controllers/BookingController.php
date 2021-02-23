<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use DTApi\Repository\UserRepository;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    protected $repository ,$userRepository ;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    // Dev Comment :
    // UserRepository should initialize in constructor
    public function __construct(BookingRepository $bookingRepository,UserRepository $userRepository)
    {
        $this->repository = $bookingRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if($user_id = $request->get('user_id')) {
            // Dev Comment: Checking user exits or not
            if($this->userRepository->getUser($user_id)){
                $response = $this->repository->getUsersJobs($user_id);
            }else{
                $response =  null;
            }

        }
        elseif($request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID'))
        {
            $response = $this->repository->getAll($request);
        }

        return response($response);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $job = $this->repository->with('translatorJobRel.user')->find($id);

        return response($job);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->all();
        try{
            // Dev Comment : Using DB Commit and rollback for storing data. It will roll back DB in case of any exception occur
            DB::beginTransaction();

            $response = $this->repository->store($request->__authenticatedUser, $data);
            DB::commit();

            return response($response);
        }catch (Throwable  $exception){
            DB::rollBack();
            // Dev Comment : Saving exception in log
            Log::info($exception);
            return response($exception->getMessage());
        }

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $data = $request->all();
        $cuser = $request->__authenticatedUser;
        try{
            // Dev Comment : Using DB Commit and rollback for storing data. It will roll back DB in case of any exception occur
            DB::beginTransaction();

            $response = $this->repository->updateJob($id, array_except($data, ['_token', 'submit']), $cuser);

            DB::commit();
            return response($response);
        }catch (Throwable  $exception){
            DB::rollBack();
            // Dev Comment : Saving exception in log
            Log::info($exception);
            return response($exception->getMessage());
        }


    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {

        $data = $request->all();
        try{
            // Dev Comment : Using DB Commit and rollback for storing data. It will roll back DB in case of any exception occur
            DB::beginTransaction();

        $response = $this->repository->storeJobEmail($data);
            DB::commit();
            return response($response);
        }catch (Throwable  $exception){
            DB::rollBack();
            // Dev Comment : Saving exception in log
            Log::info($exception);
            return response($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        if($user_id = $request->get('user_id')) {

            $response = $this->repository->getUsersJobsHistory($user_id, $request);
            return response($response);
        }

        return null;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;
        try{
            // Dev Comment : Using DB Commit and rollback for storing data. It will roll back DB in case of any exception occur
            DB::beginTransaction();

        $response = $this->repository->acceptJob($data, $user);
            DB::commit();
            return response($response);
        }catch (Throwable  $exception){
            DB::rollBack();
            // Dev Comment : Saving exception in log
            Log::info($exception);
            return response($exception->getMessage());
        }
    }

    public function acceptJobWithId(Request $request)
    {
        $data = $request->get('job_id');
        $user = $request->__authenticatedUser;
        try{
            // Dev Comment : Using DB Commit and rollback for storing data. It will roll back DB in case of any exception occur
            DB::beginTransaction();

        $response = $this->repository->acceptJobWithId($data, $user);
            DB::commit();
            return response($response);
        }catch (Throwable  $exception){
            DB::rollBack();
            // Dev Comment : Saving exception in log
            Log::info($exception);
            return response($exception->getMessage());
        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;
        try{
            // Dev Comment : Using DB Commit and rollback for storing data. It will roll back DB in case of any exception occur
            DB::beginTransaction();

            $response = $this->repository->cancelJobAjax($data, $user);

            DB::commit();
            return response($response);
        }catch (Throwable  $exception){
            DB::rollBack();
            // Dev Comment : Saving exception in log
            Log::info($exception);
            return response($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        $data = $request->all();
        try{
            // Dev Comment : Using DB Commit and rollback for storing data. It will roll back DB in case of any exception occur
            DB::beginTransaction();

        $response = $this->repository->endJob($data);

            DB::commit();
            return response($response);
        }catch (Throwable  $exception){
            DB::rollBack();
            // Dev Comment : Saving exception in log
            Log::info($exception);
            return response($exception->getMessage());
        }

    }

    public function customerNotCall(Request $request)
    {
        $data = $request->all();
        try{
            // Dev Comment : Using DB Commit and rollback for storing data. It will roll back DB in case of any exception occur
            DB::beginTransaction();

        $response = $this->repository->customerNotCall($data);

            DB::commit();
            return response($response);
        }catch (Throwable  $exception){
            DB::rollBack();
            // Dev Comment : Saving exception in log
            Log::info($exception);
            return response($exception->getMessage());
        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->getPotentialJobs($user);

        return response($response);
    }

    public function distanceFeed(Request $request)
    {
        // Dev Comment : $request->has is batter than using multiples check
        $data = $request->all();

        if ($request->has('distance')) {
            $distance = $request->distance;
        } else {
            $distance = null;
        }
        if ($request->has('time')) {
            $time = $request->time;
        } else {
            $time = null;
        }
        if ($request->has('jobid')) {
            $jobid = $request->jobid;
        }

        if ($request->has('session_time')) {
            $session =  $request->session_time;
        } else {
            $session = null;
        }

        if ($request->flagged == 'true') {
            if($request->admincomment == '') return "Please, add comment";
            $flagged = 'yes';
        } else {
            $flagged = 'no';
        }

        if ($request->manually_handled == 'true') {
            $manually_handled = 'yes';
        } else {
            $manually_handled = 'no';
        }

        if ($request->by_admin == 'true') {
            $by_admin = 'yes';
        } else {
            $by_admin = 'no';
        }

        if ($request->has('admincomment')) {
            $admincomment = $request->admincomment;
        } else {
            $admincomment = null;
        }
        if ($time || $distance) {

            $affectedRows = Distance::where('job_id', '=', $jobid)->update(array('distance' => $distance, 'time' => $time));
        }

        if ($admincomment || $session || $flagged || $manually_handled || $by_admin) {

            $affectedRows1 = Job::where('id', '=', $jobid)->update(array('admin_comments' => $admincomment, 'flagged' => $flagged, 'session_time' => $session, 'manually_handled' => $manually_handled, 'by_admin' => $by_admin));

        }

        return response('Record updated!');
    }

    public function reopen(Request $request)
    {
        $data = $request->all();
        try{
            // Dev Comment : Using DB Commit and rollback for storing data. It will roll back DB in case of any exception occur
            DB::beginTransaction();

            $response = $this->repository->reopen($data);

            DB::commit();
            return response($response);
        }catch (Throwable  $exception){
            DB::rollBack();
            // Dev Comment : Saving exception in log
            Log::info($exception);
            return response($exception->getMessage());
        }
    }

    public function resendNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);
        $this->repository->sendNotificationTranslator($job, $job_data, '*');

        return response(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);

        try {
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['success' => $e->getMessage()]);
        }
    }

}
