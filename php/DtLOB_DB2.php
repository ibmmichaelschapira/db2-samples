<?php
 /***************************************************************************
 * (c) Copyright IBM Corp. 2007 All rights reserved.
 *
 * The following sample of source code ("Sample") is owned by International
 * Business Machines Corporation or one of its subsidiaries ("IBM") and is
 * copyrighted and licensed, not sold. You may use, copy, modify, and
 * distribute the Sample in any form without payment to IBM, for the purpose
 * of assisting you in the development of your applications.
 *
 * The Sample code is provided to you on an "AS IS" basis, without warranty
 * of any kind. IBM HEREBY EXPRESSLY DISCLAIMS ALL WARRANTIES, EITHER EXPRESS
 * OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. Some jurisdictions
 * do not allow for the exclusion or limitation of implied warranties, so the
 * above limitations or exclusions may not apply to you. IBM shall not be
 * liable for any damages you suffer as a result of using, copying, modifying
 * or distributing the Sample, even if IBM has been advised of the
 * possibility of such damages.
 *
 ****************************************************************************
 *
 * SOURCE FILE NAME: DtLOB_DB2.php
 *
 * SAMPLE: How to use LOB data type
 *
 * SQL Statements USED:
 *         SELECT
 *         INSERT
 *         DELETE
 *
 ****************************************************************************
 *
 * For more information on the sample programs, see the README file.
 *
 ***************************************************************************/

require_once "UtilIOHelper.php";
require_once "UtilConnection_DB2.php";
require_once "UtilTableSetup_LOB.php";

class LOB extends DB2_Connection
{
  public $SAMPLE_HEADER =
"
echo '
THIS SAMPLE SHOWS HOW TO USE LOB DATA TYPE.
';
";
  function __construct($initialize = true)
  {
      parent::__construct($initialize);
      $this->make_Connection();
  }

  public function insert_BLOB_From_File()
  {
    $photoFormat = "gif";
    $empno = "000200";
    $BlobFileName = $this->documentRoot . "photo.gif";

    $toPrintToScreen = "
---------------------------------------------------
INSERT BLOB FILE DATA FROM A FILE INTO THE DATABASE:
  Prepare the statement:
";
    $this->format_Output($toPrintToScreen);

    // -------------- Write BLOB data into file -----------------
    $query = "
INSERT INTO {$this->schema}staff_photo (photo_format, empno, picture)
  VALUES (?, ?, ?)
";
    $this->format_Output($query);
    //Removing Excess white space.
    $query = preg_replace('/\s+/', " ", $query);
    // Prepare the SQL/XML query
    $InsertStmt = db2_prepare($this->dbconn, $query);

    $toPrintToScreen = "
    Execute the prepared statement using:
      photo_format = '$photoFormat'
      empno = '$empno'
      picture = Photo.gif
";
    $this->format_Output($toPrintToScreen);

    db2_bind_param($InsertStmt, 1, "photoFormat", DB2_PARAM_IN);
    db2_bind_param($InsertStmt, 2, "empno", DB2_PARAM_IN);
    db2_bind_param($InsertStmt, 3, "BlobFileName", DB2_PARAM_FILE);

    if(db2_execute($InsertStmt))
    {
      $toPrintToScreen = "
  INSERT BLOB FILE TO THE DATABASE SUCCESSFULLY!
";
      $this->format_Output($toPrintToScreen);
      $this->commit();
    }
    else
    {
      $this->format_Output(db2_stmt_errormsg());
    }
  }
  public function insert_CLOB_From_File()
  {
    $empno = "000200";
    $resume_format = "ascii";
    $ClobFileName = $this->documentRoot . "resume.txt";
    $toPrintToScreen = "
---------------------------------------------------
INSERT CLOB FILE DATA FROM A FILE INTO THE DATABASE:
  Prepare the statement:
";

    $this->format_Output($toPrintToScreen);
    $query = "
INSERT INTO {$this->schema}staff_resume (empno, resume_format, resume) VALUES (?, ?, ?)
  ";
    $this->format_Output($query);
    //Removing Excess white space.
    $query = preg_replace('/\s+/', " ", $query);
    // Prepare the SQL/XML query
    $InsertStmt = db2_prepare($this->dbconn, $query);

    $toPrintToScreen = "
    Execute the prepared statement using:
      resume_format = '$resume_format'
      empno = '$empno'
      resume = resume.txt
";
    $this->format_Output($toPrintToScreen);

    db2_bind_param($InsertStmt, 1, "empno", DB2_PARAM_IN);
    db2_bind_param($InsertStmt, 2, "resume_format", DB2_PARAM_IN);
    db2_bind_param($InsertStmt, 3, "ClobFileName", DB2_PARAM_FILE);

    if(db2_execute($InsertStmt))
    {
      $toPrintToScreen = "
  INSERT CLOB FILE TO THE DATABASE SUCCESSFULLY!
";
      $this->format_Output($toPrintToScreen);
      $this->commit();
    }
    else
    {
      $this->format_Output(db2_stmt_errormsg());
    }
  }

  public function blob_File_Use()
  {
      $empno = "000200";
      $photoFormat = "gif";

      $toPrintToScreen = "
----------------------------------------------------------
USE THE SQL STATEMENTS:
  SELECT
  INSERT
  DELETE
TO SHOW HOW TO USE BINARY LARGE OBJECT (BLOB) FILES.

---------------------------------------------------
  SELECT BLOB DATA FROM THE DATABASE:
    Prepare the statement:
";
    $this->format_Output($toPrintToScreen);

      // ---------- Read BLOB data from file -------------------

      $query = "
SELECT picture
  FROM {$this->schema}staff_photo
  WHERE photo_format = ? AND empno = ?
";
      $this->format_Output($query);
      //Removing Excess white space.
      $query = preg_replace('/\s+/', " ", $query);
      // Prepare the SQL/XML query
      $stmt = db2_prepare($this->dbconn, $query);

      $toPrintToScreen = "
    Execute the prepared statement using:
      photo_format = '$photoFormat'
      empno = '$empno'
";
      $this->format_Output($toPrintToScreen);

      db2_bind_param($stmt, 1, "photoFormat", DB2_PARAM_IN);
      db2_bind_param($stmt, 2, "empno", DB2_PARAM_IN);
      if(db2_execute($stmt))
      {
        $a_result = db2_fetch_array($stmt);
        $The_Blob = $a_result[0];
        db2_free_stmt($stmt);
        $toPrintToScreen = "
  READ FROM BLOB FILE SUCCESSFULLY!
---------------------------------------------------
INSERT BLOB FILE DATA BACK INTO THE DATABASE:
  Prepare the statement:
";
       $this->format_Output($toPrintToScreen);

       // -------------- Write BLOB data into file -----------------
      $query = "
INSERT INTO {$this->schema}staff_photo (photo_format, empno, picture)
  VALUES (?, ?, ?)
";
      $this->format_Output($query);
      //Removing Excess white space.
      $query = preg_replace('/\s+/', " ", $query);
      // Prepare the SQL/XML query
      $InsertStmt = db2_prepare($this->dbconn, $query);

      $empno = "000120";

      $toPrintToScreen = "
    Execute the prepared statement using:
      photo_format = '$photoFormat'
      empno = '$empno'
    And the blob object that we read in eariler.
";
      $this->format_Output($toPrintToScreen);

      db2_bind_param($InsertStmt, 1, "photoFormat", DB2_PARAM_IN);
      db2_bind_param($InsertStmt, 2, "empno", DB2_PARAM_IN);
      db2_bind_param($InsertStmt, 3, "The_Blob", DB2_PARAM_IN);

      if(db2_execute($InsertStmt))
      {
        $toPrintToScreen = "
  INSERT BLOB FILE TO DB SUCCESSFULLY!
";
        $this->format_Output($toPrintToScreen);
        $this->commit();
      }
      else
      {
        $this->format_Output(db2_stmt_errormsg());
      }
    }
    else
    {
      $this->format_Output(db2_stmt_errormsg());
    }

  } // blob_File_Use

  public function clob_Use()
  {

    $empno = "000200";
    $resume_format = "ascii";

    $toPrintToScreen = "
----------------------------------------------------------
USE THE SQL STATEMENTS:
  SELECT
  INSERT
  DELETE
TO SHOW HOW TO USE CHARACTER LARGE OBJECT (CLOB) DATA TYPE.

---------------------------------------------------
  READ CLOB DATA TYPE:
   Note: resume is a CLOB data type!
   Execute the statement:
";
    $this->format_Output($toPrintToScreen);


    // ----------- Read CLOB data type from DB ----------------

    $query = "
SELECT resume
  FROM {$this->schema}staff_resume
  WHERE resume_format = ? AND empno = ?
";
    $this->format_Output($query);
    //Removing Excess white space.
    $query = preg_replace('/\s+/', " ", $query);
    // Prepare the SQL/XML query
    $stmt = db2_prepare($this->dbconn, $query);

    $toPrintToScreen = "
    Execute the prepared statement using:
      resume_format = '$resume_format'
      empno = '$empno'
";
    $this->format_Output($toPrintToScreen);

    db2_bind_param($stmt, 1, "resume_format", DB2_PARAM_IN);
    db2_bind_param($stmt, 2, "empno", DB2_PARAM_IN);

    if(db2_execute($stmt))
    {
      $a_result = db2_fetch_array($stmt);
      $The_Clob = $a_result[0];
      db2_free_stmt($stmt);

      $toPrintToScreen = "
 READ CLOB DATA TYPE FROM DB SUCCESSFULLY!
";
      $this->format_Output($toPrintToScreen);

      // ------------ Display the CLOB data onto the screen -------
      $clobLength = strlen($The_Clob);

      $toPrintToScreen = "
---------------------------------------------------
  HERE IS THE RESUME WITH A LENGTH OF $clobLength
CHARACTERS.
";
      $this->format_Output($toPrintToScreen);


      $this->format_Output($The_Clob);

      $toPrintToScreen = "
    --- END OF RESUME ---
";
      $this->format_Output($toPrintToScreen);
    }
    else
    {
      $this->format_Output(db2_stmt_errormsg());
    }
    $this->commit();
  } // clob_Use

  public function clob_File_Use()
  {

    $empno = "000200";
    $resume_format = "ascii";

    $toPrintToScreen = "
----------------------------------------------------------
USE THE SQL STATEMENTS:
  SELECT
TO SHOW HOW TO USE CHARACTER LARGE OBJECT (CLOB) DATA TYPE.

---------------------------------------------------
READ CLOB DATA TYPE:
 Note: resume is a CLOB data type!
    Execute the statement:
";
    $this->format_Output($toPrintToScreen);

    $fileName = "resume_new.txt";

      // ----------- Read CLOB data type from DB -----------------
    $query = "
SELECT resume
  FROM {$this->schema}staff_resume
  WHERE resume_format = ? AND empno = ?
";
    $this->format_Output($query);
    //Removing Excess white space.
    $query = preg_replace('/\s+/', " ", $query);
    // Prepare the SQL/XML query
    $stmt = db2_prepare($this->dbconn, $query);

    $toPrintToScreen = "
    Execute the prepared statement using:
      resume_format = '$resume_format'
      empno = '$empno'
";
    $this->format_Output($toPrintToScreen);

    db2_bind_param($stmt, 1, "resume_format", DB2_PARAM_IN);
    db2_bind_param($stmt, 2, "empno", DB2_PARAM_IN);

    if(db2_execute($stmt))
    {
      $a_result = db2_fetch_array($stmt);
      $The_Clob = $a_result[0];
      db2_free_stmt($stmt);

      $toPrintToScreen = "
  READ CLOB DATA TYPE DB SUCCESSFULLY!
";
    $this->format_Output($toPrintToScreen);

      // ---------- Write CLOB data into file -------------------
      $clobLength = strlen($The_Clob);

      $toPrintToScreen = "
---------------------------------------------------
  WRITE THE CLOB DATA THAT WE GET FROM ABOVE INTO THE
FILE '$fileName'
";
    $this->format_Output($toPrintToScreen);

      if(($letters = fopen($fileName, "w")) !== FALSE)
      {
        if(fwrite($letters, $The_Clob) !== false)
        {
        	fclose($letters);
            $toPrintToScreen = "
  WRITE CLOB DATA TYPE INTO FILE SUCCESSFULLY!
";
            $this->format_Output($toPrintToScreen);
        }
        else
        {
             $toPrintToScreen = "
  WRITE FAILD!
";
            $this->format_Output($toPrintToScreen);
        }
      }
      else
      {
             $toPrintToScreen = "
  FILE OPEN FAILD!
";
            $this->format_Output($toPrintToScreen);
      }
    }
    else
    {
      $this->format_Output(db2_stmt_errormsg());
    }
    $this->commit();
  } // clob_File_Use

  public function clob_Search_String_Use()
  {

    $empno = "000200";
    $resume_format = "ascii";

    $toPrintToScreen = "
----------------------------------------------------------
USE THE SQL STATEMENTS:
 SELECT

TO SHOW HOW TO SEARCH A SUBSTRING WITHIN A CLOB OBJECT.

---------------------------------------------------
 READ CLOB DATA TYPE:
 Execute the statement:
";
    $this->format_Output($toPrintToScreen);

      // ----------- Read CLOB data from file -------------------
    $query = "
SELECT resume
  FROM {$this->schema}staff_resume
  WHERE resume_format = ? AND empno = ?
";
    $this->format_Output($query);
    //Removing Excess white space.
    $query = preg_replace('/\s+/', " ", $query);
    // Prepare the SQL/XML query
    $stmt = db2_prepare($this->dbconn, $query);

    $toPrintToScreen = "
    Execute the prepared statement using:
      resume_format = '$resume_format'
      empno = '$empno'
";
    $this->format_Output($toPrintToScreen);

    db2_bind_param($stmt, 1, "resume_format", DB2_PARAM_IN);
    db2_bind_param($stmt, 2, "empno", DB2_PARAM_IN);

    if(db2_execute($stmt))
    {
      $a_result = db2_fetch_array($stmt);
      $The_Clob = $a_result[0];
      db2_free_stmt($stmt);

      $toPrintToScreen = "
READ CLOB DATA TYPE FROM DB SUCCESSFULLY!
";
      $this->format_Output($toPrintToScreen);

      // ------ Display the ORIGINAL CLOB data onto the screen -------

      $clobLength = strlen($The_Clob);

      $toPrintToScreen = "
 The original CLOB is $clobLength bytes long.

***************************************************
ORIGINAL RESUME -- VIEW
***************************************************
";
      $this->format_Output($toPrintToScreen);

      $this->format_Output($The_Clob);

      $toPrintToScreen = "

-- END OF ORIGINAL RESUME --

***************************************************
NEW RESUME -- CREATE
***************************************************
";
      $this->format_Output($toPrintToScreen);

      // Determine the starting position of each section of the resume
      $StartPosition['Resume Start']           = 0; //this is the 'Resume: Delores M. Quintana' part
      $StartPosition['Personal Information']   = stripos($The_Clob, "Personal Information");
      $StartPosition['Department Information'] = stripos($The_Clob, "Department Information");
      $StartPosition['Education']              = stripos($The_Clob, "Education");
      $StartPosition['Work History']           = stripos($The_Clob, "Work History");
      $StartPosition['Interests']              = stripos($The_Clob, "Interests");
      $StartPosition['END']                    = strlen($The_Clob) - 1;

      // Determine the ending position of each section of the resume
      $EndPosition['END'] = 0;
      $LastPosition = 0;
      asort($StartPosition);
      foreach ($StartPosition as $key => $val)
      {
      	if($LastPosition === 0)
        {
        	$LastPosition = $key;
        }
        else
        {
          $EndPosition[$LastPosition] = $val-1;
          $LastPosition = $key;
        }
      }

      $toPrintToScreen = "
 Create new resume with Department info at end.
";
      $this->format_Output($toPrintToScreen);

      // Create a separate String for each section of the resume
      $ResumeSections['Resume Start']   = substr($The_Clob, $StartPosition['Resume Start'], $EndPosition['Resume Start'] - $StartPosition['Resume Start']);
      $ResumeSections['Personal Information'] = substr($The_Clob, $StartPosition['Personal Information'], $EndPosition['Personal Information'] - $StartPosition['Personal Information']);
      $ResumeSections['Department Information'] = substr($The_Clob, $StartPosition['Department Information'], $EndPosition['Department Information'] - $StartPosition['Department Information']);
      $ResumeSections['Education'] = substr($The_Clob, $StartPosition['Education'], $EndPosition['Education'] - $StartPosition['Education']);
      $ResumeSections['Work History']  = substr($The_Clob, $StartPosition['Work History'], $EndPosition['Work History'] - $StartPosition['Work History']);
      $ResumeSections['Interests']  = substr($The_Clob, $StartPosition['Interests'], $EndPosition['Interests'] - $StartPosition['Interests']);

      // Concatenate the sections in the desired order
      $newClobString = $ResumeSections['Resume Start'] .
                       $ResumeSections['Personal Information'] .
                       $ResumeSections['Education'] .
                       $ResumeSections['Work History'] .
                       $ResumeSections['Interests'] .
                       "\n\n\n" .
                       $ResumeSections['Department Information'];

      // Put the new resume in the database but use a different employee number, 000120, so that the
      // original row is not overlaid.
      $toPrintToScreen = "
 Insert the new resume into the database.
";
      $empno = "000120";

      $this->format_Output($toPrintToScreen);
      $query = "
INSERT INTO {$this->schema}staff_resume (empno, resume_format, resume) VALUES (?, ?, ?)
  ";
      $this->format_Output($query);
      //Removing Excess white space.
      $query = preg_replace('/\s+/', " ", $query);
      // Prepare the SQL/XML query
      $InsertStmt = db2_prepare($this->dbconn, $query);

      $toPrintToScreen = "
    Execute the prepared statement using:
      resume_format = '$resume_format'
      empno = '$empno'
      resume = New Resume in memory
";
      $this->format_Output($toPrintToScreen);

      db2_bind_param($InsertStmt, 1, "empno", DB2_PARAM_IN);
      db2_bind_param($InsertStmt, 2, "resume_format", DB2_PARAM_IN);
      db2_bind_param($InsertStmt, 3, "newClobString", DB2_PARAM_IN);

      if(db2_execute($InsertStmt))
      {
        db2_free_stmt($InsertStmt);

        // ----------- Read the NEW RESUME (CLOB) from DB ------------
        $toPrintToScreen = "
***************************************************
NEW RESUME -- VIEW
***************************************************
---------------------------------------------------
READ CLOB DATA TYPE:

 Execute the statement:
";
        $this->format_Output($toPrintToScreen);

        $query = "
SELECT resume FROM {$this->schema}staff_resume WHERE empno = ? AND resume_format = ?
    ";
        $this->format_Output($query);
        //Removing Excess white space.
        $query = preg_replace('/\s+/', " ", $query);
        // Prepare the SQL/XML query
        $SelectStmt = db2_prepare($this->dbconn, $query);

        $toPrintToScreen = "
    Execute the prepared statement using:
      resume_format = '$resume_format'
      empno = '$empno'
";
        $this->format_Output($toPrintToScreen);

        db2_bind_param($SelectStmt, 1, "empno");
        db2_bind_param($SelectStmt, 2, "resume_format");

        if(db2_execute($SelectStmt))
        {
          $a_result = db2_fetch_array($SelectStmt);
          $The_New_Clob = $a_result[0];
          db2_free_stmt($SelectStmt);

          $toPrintToScreen = "
 READ NEW RESUME (CLOB) FROM DB SUCCESSFULLY!
";
          $this->format_Output($toPrintToScreen);

           // ------ Display the NEW RESUME (CLOB) onto the screen -------
          $NewclobLength = strlen($The_New_Clob);

          $toPrintToScreen = "
 The new CLOB is $NewclobLength bytes long.

---------------------------------------------------
 HERE IS THE NEW RESUME:
";
          $this->format_Output($toPrintToScreen);

          $this->format_Output($The_New_Clob);

     $toPrintToScreen = "
 -- END OF NEW RESUME --
";
          $this->format_Output($toPrintToScreen);

          $this->commit();
        }
        else
        {
          $toPrintToScreen = "
  Reading of New Resume FAILD!
";
          $this->format_Output($toPrintToScreen);
          $this->format_Output(db2_stmt_errormsg());
        }
      }
      else
      {
         $toPrintToScreen = "
  Writing of new Resume FAILD!
";
        $this->format_Output($toPrintToScreen);
        $this->format_Output(db2_stmt_errormsg());
      }
    }
    else
    {
      $toPrintToScreen = "
  Reading of Resume FAILD!
";
      $this->format_Output($toPrintToScreen);
      $this->format_Output(db2_stmt_errormsg());
    }
  } // clob_Search_String_Use
} // DtLob Class


$Run_Sample = new LOB();

TABLE_SETUP_General_LOB::CREATE($Run_Sample);

$Run_Sample->insert_BLOB_From_File();
$Run_Sample->insert_CLOB_From_File();
$Run_Sample->blob_File_Use();
$Run_Sample->clob_Use();
$Run_Sample->clob_File_Use();
$Run_Sample->clob_Search_String_Use();

 /*******************************************************
  * We rollback at the end of all samples to ensure that
  * there are not locks on any tables. The sample as is
  * delivered does not need this. The author of these
  * samples expects that you the read of this comment
  * will play and learn from them and the reader may
  * forget commit or rollback their action as the
  * author has in the past.
  * As such this is here:
  ******************************************************/
$Run_Sample->rollback();

TABLE_SETUP_General_LOB::DROP($Run_Sample);

// Close the database connection
$Run_Sample->close_Connection();

?>
