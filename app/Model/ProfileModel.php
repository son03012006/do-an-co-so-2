<?php

class ProfileModel
{
    private $db;

    public function __construct($dbh)
    {
        $this->db = $dbh;
    }

    public function getProfile($sid)
    {
        $sql = "SELECT * FROM tblstudents WHERE StudentId = :sid";
        $q = $this->db->prepare($sql);
        $q->bindParam(':sid', $sid, PDO::PARAM_STR);
        $q->execute();
        return $q->fetch(PDO::FETCH_OBJ);
    }

    public function updateProfile($sid, $fullname, $mobileno, $avatar = null)
    {
        if ($avatar) {
            $sql = "UPDATE tblstudents
                    SET FullName = :fname,
                        MobileNumber = :mobileno,
                        ProfileImage = :avatar
                    WHERE StudentId = :sid";
        } else {
            $sql = "UPDATE tblstudents
                    SET FullName = :fname,
                        MobileNumber = :mobileno
                    WHERE StudentId = :sid";
        }

        $q = $this->db->prepare($sql);
        $q->bindParam(':sid', $sid);
        $q->bindParam(':fname', $fullname);
        $q->bindParam(':mobileno', $mobileno);

        if ($avatar) {
            $q->bindParam(':avatar', $avatar);
        }

        return $q->execute();
    }
}
