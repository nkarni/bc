<?php

class ControllerCatalogFiles extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/files');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->getList();
  }

  public function delete() {
		$this->load->language('catalog/files');
		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $file) {
        unlink(DIR_FILES . $file);
			}
		}
    $this->response->redirect($this->url->link('catalog/files', 'token=' . $this->session->data['token'], true));
  }

  public function edit() {
    $json = array();

		if (isset($this->request->post['from']) && isset($this->request->post['to'])) {
      if (is_file(DIR_FILES . $this->request->post['from'])) {
        $json['filename'] = $this->request->post['to'];
        $json['success'] = $this->language->get('text_rename');
        rename(DIR_FILES . $this->request->post['from'], DIR_FILES . basename($this->request->post['to']));
      }
      else {
				$json['error'] = $this->language->get('error_upload');
      }
    }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

  public function getList() {
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_edit'] = $this->language->get('button_edit');

		$data['column_filename'] = $this->language->get('column_filename');
		$data['column_url'] = $this->language->get('column_url');
		$data['column_action'] = $this->language->get('column_action');

		$data['delete'] = $this->url->link('catalog/files/delete', 'token=' . $this->session->data['token'], true);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/files', 'token=' . $this->session->data['token'], true)
		);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['files'] = array();
    foreach(glob(DIR_FILES . "*") as $file)
    {
      if (basename($file) === "index.html") continue;
      $data['files'][] = array(
        "filename" => basename($file),
        "url" => HTTPS_CATALOG . "files/" . rawurlencode(basename($file))
      );
    }

		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/files_list', $data));
  }

  public function upload() {
		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'catalog/download')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

				// Validate the filename length
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}

				// Allowed file extension types
				$allowed = array();

				$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

				$filetypes = explode("\n", $extension_allowed);

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Allowed file mime types
				$allowed = array();

				$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

				$filetypes = explode("\n", $mime_allowed);

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($this->request->files['file']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}

		if (!$json) {
			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_FILES . $filename);

			$json['filename'] = $filename;
			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }
}
