<?php

namespace Employee\EmployeeBundle\Controller;

use Employee\EmployeeBundle\Classes\ApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EmployeeController extends Controller
{
    /** duration of a week in seconds */
    const WEEK = 604800;

    /**
     * Overview of all employees
     *
     * @Route("/", name="_overview")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'employeesGrouped' => $this->getEmployees()
        );
    }

    /**
     * View details of an employee
     *
     * @param string $email The email of the employee to get
     *
     * @Route("/view/{email}", name="_detail")
     * @Template()
     */
    public function detailAction($email)
    {
        $employeePool = $this->getEmployees();
        return new JsonResponse(
            json_encode($this->getEmployee($email, $employeePool))
        );
    }

    /**
     * Get the employees either from memcached or curl
     *
     * @return array A list of employees grouped per platform category
     */
    private function getEmployees()
    {
        $employeesGrouped = $this->get('memcache.default')->get('employees');
        if (! $employeesGrouped) {
            $employeesGrouped = ApiClient::getEmployees();

            // add the base64 contents of the thumbnail to keep things fast
            foreach ($employeesGrouped as $platform => $employees) {
                foreach ($employees as $key => $employee) {
                    if (array_key_exists("photoUrl", $employee)) {
                        $employeesGrouped[$platform][$key]["photoData"] = $this->getThumbnail($employee["photoUrl"]);
                    }
                }
            }

            $this->get('memcache.default')->set('employees', $employeesGrouped, self::WEEK);
        }

        return $employeesGrouped;
    }

    /**
     * Get the base64 contents of a thumbnail
     *
     * @param string $thumbnail Name of the thumbnail
     *
     * @return string the base64 encoded thumbnail
     */
    private function getThumbnail($thumbnail)
    {
        $base64Thumbnail = $this->get('memcache.default')->get('thumbnail.' . $thumbnail);
        if (! $base64Thumbnail) {
            $base64Thumbnail = base64_encode(ApiClient::getImage($thumbnail));
            $this->get('memcache.default')->set('thumbnail.' . $thumbnail, $base64Thumbnail, self::WEEK);
        }
        return $base64Thumbnail;
    }

    /**
     * Get the information of an employee identified by his email
     *
     * @param string $email The email of the employee to get
     * @param array $employeesGrouped The pool of employees to look in grouped by platform
     *
     * @return array The employee
     */
    private function getEmployee($email, $employeesGrouped)
    {
        foreach ($employeesGrouped as $platform => $employees) {
            foreach ($employees as $employee) {
                if (array_key_exists("emailAddress", $employee) && $employee["emailAddress"] === $email) {
                    $employee["platform"] = $platform;
                    return $employee;
                }
            }
        }
        return array();
    }
}
