<?php

namespace App\Models\NewCaseQF\DocumentIndex;

use CodeIgniter\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DocumentIndex_model extends Model
{

    function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
        //$this->load->library('guzzle');
    }

    public function save_pdf_details($data, $data_2, $doc_id, $breadcrumb_step_no, $breadcrumb_to_remove, $index_id = null)
    {
        $this->db->transStart();

        if ($index_id) {
            $this->db->where('doc_id', $index_id)
                ->where('is_deleted', false)
                ->update('tbl_efiled_docs', $data);

            if ($this->db->affectedRows() > 0) {
                $this->db->where('doc_id', $doc_id)
                    ->update('tbl_uploaded_pdfs', $data_2);

                $this->db->transComplete();
            }
        } else {
            $this->db->insert('tbl_efiled_docs', $data);

            if ($this->db->insertID()) {
                $status = $this->update_breadcrumbs(session('efiling_details.registration_id'), $breadcrumb_step_no);
                $status = $this->remove_breadcrumb(session('efiling_details.registration_id'), $breadcrumb_to_remove);

                $this->db->transComplete();
            }
        }

        if ($this->db->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }


    public function delete_index($index_id, $breadcrumb_to_remove)
    {
        // Check if registration_id is set in session
        if (!session()->has('efiling_details.registration_id') || empty(session('efiling_details.registration_id'))) {
            return false;
        }

        $reg_id = session('efiling_details.registration_id');

        // Fetch necessary data for deletion
        $builder = $this->db->table('tbl_efiled_docs pdfs');
        $builder->select('pdfs.file_path, pdfs.file_name, left_pdfs.left_pdfs_count');
        $builder->join('(SELECT registration_id, COUNT(doc_id) AS left_pdfs_count 
                    FROM tbl_efiled_docs 
                    WHERE registration_id = ' . $reg_id . ' AND is_deleted = FALSE 
                    GROUP BY registration_id) left_pdfs', 'pdfs.registration_id = left_pdfs.registration_id', 'left');
        $builder->where('pdfs.doc_id', $index_id);
        $builder->where('pdfs.registration_id', $reg_id);
        $builder->where('pdfs.uploaded_by', session('login.id'));
        $builder->where('pdfs.is_deleted', false);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
        } else {
            return false;
        }

        $this->db->transStart();

        // Update tbl_efiled_docs to mark document as deleted
        $data = [
            'is_deleted' => true,
            'deleted_by' => session('login.id'),
            'deleted_on' => date('Y-m-d H:i:s'),
            'delete_ip' => $_SERVER['REMOTE_ADDR'] ?? null, // Ensure $_SERVER['REMOTE_ADDR'] is available in CI4 context
        ];
        $this->db->where('registration_id', $reg_id);
        $this->db->where('doc_id', $index_id);
        $this->db->where('uploaded_by', session('login.id'));
        $this->db->update('tbl_efiled_docs', $data);

        if ($this->db->affectedRows() == 1) {
            // Update breadcrumbs based on left_pdfs_count
            $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove[1]);
            if (($result->left_pdfs_count - 1) == 0) {
                $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove[0]);
                if ($status) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        }

        if ($this->db->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }


    public function delete_index_by_UploadFile_Pdf_Id($pdf_id, $breadcrumb_to_remove)
    {
        // Check if registration_id is set in session
        if (!session()->has('efiling_details.registration_id') || empty(session('efiling_details.registration_id'))) {
            return false;
        }

        $reg_id = session('efiling_details.registration_id');

        // Fetch file details for deletion and count of remaining PDFs
        $builder = $this->db->table('tbl_efiled_docs pdfs');
        $builder->select('pdfs.file_path, pdfs.file_name, left_pdfs.left_pdfs_count');
        $builder->join('(SELECT registration_id, COUNT(doc_id) AS left_pdfs_count 
                        FROM tbl_efiled_docs 
                        WHERE registration_id = ' . $reg_id . ' AND is_deleted = FALSE AND is_active = TRUE 
                        GROUP BY registration_id) left_pdfs', 'pdfs.registration_id = left_pdfs.registration_id', 'left');
        $builder->where('pdfs.pdf_id', $pdf_id);
        $builder->where('pdfs.registration_id', $reg_id);
        $builder->where('pdfs.uploaded_by', session('login.id'));
        $builder->where('pdfs.is_deleted', false);
        $builder->where('pdfs.is_active', true);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
        } else {
            return false;
        }

        $this->db->transStart();

        // Delete physical files associated with PDFs
        foreach ($this->getindexfiledetails($pdf_id) as $path) {
            $file_delete_status = unlink($path->file_path . $path->file_name);
            // Ensure proper error handling for file deletion
            if (!$file_delete_status) {
                $this->db->transRollback();
                return false;
            }
        }

        // Update tbl_efiled_docs to mark documents as deleted
        $data = [
            'is_deleted' => true,
            'deleted_by' => session('login.id'),
            'deleted_on' => date('Y-m-d H:i:s'),
            'delete_ip' => $_SERVER['REMOTE_ADDR'] ?? null, // Ensure $_SERVER['REMOTE_ADDR'] is available in CI4 context
        ];
        $this->db->where('registration_id', $reg_id);
        $this->db->where('pdf_id', $pdf_id);
        $this->db->where('uploaded_by', session('login.id'));
        $this->db->update('tbl_efiled_docs', $data);

        if ($this->db->affectedRows() >= 1) {
            // Update breadcrumbs based on left_pdfs_count
            $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove[1]);
            if (($result->left_pdfs_count - 1) == 0) {
                $status = $this->remove_breadcrumb($reg_id, $breadcrumb_to_remove[0]);
                if ($status) {
                    $this->db->transComplete();
                }
            } else {
                $this->db->transComplete();
            }
        } else {
            $this->db->transRollback(); // Rollback transaction if update fails
            return false;
        }

        return $this->db->transStatus(); // Return transaction status
    }


    public function update_breadcrumbs($registration_id, $step_no)
    {
        // Check if registration_id is set in session
        if (!session()->has('efiling_details.registration_id') || empty(session('efiling_details.registration_id'))) {
            return false;
        }

        // Construct new breadcrumbs array
        $old_breadcrumbs = session('efiling_details.breadcrumb_status') . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);

        // Update breadcrumbs in database
        $builder = $this->db->table('tbl_efiling_nums');
        $builder->where('registration_id', $registration_id);
        $builder->update(['breadcrumb_status' => $new_breadcrumbs]);

        // Check if update was successful
        if ($builder->affectedRows() > 0) {
            // Update session variable with new breadcrumbs
            session()->set('efiling_details.breadcrumb_status', $new_breadcrumbs);
            return true;
        } else {
            return false;
        }
    }


    public function remove_breadcrumb($registration_id, $breadcrumb_to_remove)
    {
        // Check if registration_id is set in session
        if (!session()->has('efiling_details.registration_id') || empty(session('efiling_details.registration_id'))) {
            return false;
        }

        // Get current breadcrumbs from session
        $breadcrumbs_array = explode(',', session('efiling_details.breadcrumb_status'));

        // Check if breadcrumb to remove exists in array
        if (in_array($breadcrumb_to_remove, $breadcrumbs_array)) {
            // Find index of breadcrumb to remove and unset it
            $index = array_search($breadcrumb_to_remove, $breadcrumbs_array);
            if ($index !== false) {
                unset($breadcrumbs_array[$index]);
            }

            // Construct new breadcrumbs string
            $new_breadcrumbs = implode(',', $breadcrumbs_array);

            // Update breadcrumbs in database
            $builder = $this->db->table('tbl_efiling_nums');
            $builder->where('registration_id', $registration_id);
            $builder->update(['breadcrumb_status' => $new_breadcrumbs]);

            // Check if update was successful
            if ($builder->affectedRows() > 0) {
                // Update session variable with new breadcrumbs
                session()->set('efiling_details.breadcrumb_status', $new_breadcrumbs);
                return true;
            } else {
                return false;
            }
        }

        return false; // Breadcrumb to remove not found
    }


    public function getPSPdfKitDocumentId_fromIndexedDocId($indexid)
    {
        // Check if login details are set in session
        if (!session()->has('login.id') || empty(session('login.id'))) {
            return null;
        }

        // Build query using Query Builder
        $builder = $this->db->table('tbl_efiled_docs');
        $builder->select('pspdfkit_document_id');
        $builder->where('doc_id', $indexid);
        $builder->where('uploaded_by', session('login.id'));
        $builder->where('is_deleted', false);

        // Execute query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() > 0) {
            // Fetch the first row and return pspdfkit_document_id
            $result = $query->getRow();
            return $result->pspdfkit_document_id;
        } else {
            return null;
        }
    }

    public function getPSPdfKitDocumentId_fromUploadedPdfId($pdfid)
    {
        // Check if login details are set in session
        if (!session()->has('login.id') || empty(session('login.id'))) {
            return null;
        }

        // Build query using Query Builder
        $builder = $this->db->table('tbl_efiled_docs');
        $builder->select('pspdfkit_document_id');
        $builder->where('pdf_id', $pdfid);
        $builder->where('uploaded_by', session('login.id'));
        $builder->where('is_deleted', false);

        // Execute query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() > 0) {
            // Fetch the result row and return pspdfkit_document_id
            $result = $query->getRow();
            return $result->pspdfkit_document_id;
        } else {
            return null;
        }
    }


    public function setDuplicatePSPdfKitDocument($registration_id, $old_pspdfkit_document_id, $new_pspdfkit_document_id)
    {
        // Build data array for update
        $data = [
            'pspdfkit_document_id' => $new_pspdfkit_document_id
        ];

        // Build query using Query Builder
        $builder = $this->db->table('tbl_efiled_docs');
        $builder->where('registration_id', $registration_id);
        $builder->where('pspdfkit_document_id', $old_pspdfkit_document_id);
        $builder->where('is_active', true);
        $builder->where('is_deleted', false);
        $builder->update($data);
    }



    public function deleteDocument($document_id)
    {
        $pspdfkit_document = false;

        try {
            // Initialize Guzzle HTTP client
            $client = new Client();

            // Send DELETE request to PSPDFKit server
            $response = $client->delete(
                PSPDFKIT_SERVER_URI . '/api/documents/' . $document_id,
                [
                    'headers' => [
                        'Authorization' => 'Token token="secret"',
                    ],
                    'http_errors' => false
                ]
            );

            // Check if request was successful (status code 200)
            if ($response->getStatusCode() == 200) {
                $pspdfkit_document = true;
            }
        } catch (GuzzleException $e) {
            // Handle any exceptions thrown by Guzzle
            // For example, log the error or handle gracefully
            //$pspdfkit_document = false;
        }

        return $pspdfkit_document;
    }


    public function getIndexedDocFromPspdfkitDocumentId($pspdfkit_document_id)
    {
        // Build query using Query Builder
        $builder = $this->db->table('tbl_efiled_docs');
        $builder->select('*');
        $builder->where('pspdfkit_document_id', $pspdfkit_document_id);
        $builder->where('is_active', true);
        $builder->where('is_deleted', false);

        // Execute query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() > 0) {
            // Fetch the first result and return it
            return $query->getRow();
        } else {
            return null;
        }
    }


    //code added on 21 nov 20
    public function getindexfiledetails($pdfid)
    {
        // Build query using Query Builder
        $builder = $this->db->table('tbl_efiled_docs');
        $builder->select('*');
        $builder->where('pdf_id', $pdfid);
        $builder->where('uploaded_by', $_SESSION['login']['id']);
        $builder->where('is_deleted', false);
        $builder->orderBy('st_page', 'ASC'); // Sequentially arrange all indexed PDFs

        // Execute query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() > 0) {
            // Fetch all results and return them as an array of objects
            return $query->getResult();
        } else {
            return null;
        }
    }


    //Code added on 23 nov 20
    public function getmasterfiledetails($pdfid)
    {
        // Build query using Query Builder
        $builder = $this->db->table('tbl_uploaded_pdfs');
        $builder->select('pspdfkit_document_id, file_name, file_path, page_no');
        $builder->where('doc_id', $pdfid);
        $builder->where('uploaded_by', $_SESSION['login']['id']);
        $builder->where('is_deleted', false);

        // Execute query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() > 0) {
            // Fetch all results and return them as an array of objects
            return $query->getResult();
        } else {
            return null;
        }
    }

    //code added on 21 nov 20
    public function getindexfiledetails_perindex($pdfid, $index_id)
    {
        // Build query using Query Builder
        $builder = $this->db->table('tbl_efiled_docs');
        $builder->select('*');
        $builder->where('pdf_id', $pdfid);
        $builder->where('uploaded_by', $_SESSION['login']['id']);
        $builder->where('is_deleted', false);
        $builder->where('is_active', true);
        $builder->where('doc_id !=', $index_id);
        $builder->orderBy('st_page', 'ASC'); // Sequentially arrange all indexed PDFs

        // Execute query
        $query = $builder->get();

        // Check if results are found
        if ($query->getNumRows() > 0) {
            // Fetch all results and return them as an array of objects
            return $query->getResult();
        } else {
            return null;
        }
    }
}
