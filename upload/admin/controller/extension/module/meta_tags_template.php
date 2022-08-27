<?php
class ControllerExtensionModuleMetaTagsTemplate extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/meta_tags_template');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('meta_tags_template', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/meta_tags_template', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/module/meta_tags_template', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/meta_tags_template', 'user_token=' . $this->session->data['user_token'], true);

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['meta_tags_template'] = array();

		foreach ($data['languages'] as $language_code => $language_info) {
			if (isset($this->request->post['meta_tags_template_meta_title_' . $language_info['language_id']])) {
				$data['meta_tags_template']['meta_title'][$language_info['language_id']] = $this->request->post['meta_tags_template_meta_title_' . $language_info['language_id']];
			} else {
				$data['meta_tags_template']['meta_title'][$language_info['language_id']] = $this->config->get('meta_tags_template_meta_title_' . $language_info['language_id']);
			}

			if (isset($this->request->post['meta_tags_template_meta_description_' . $language_info['language_id']])) {
				$data['meta_tags_template']['meta_description'][$language_info['language_id']] = $this->request->post['meta_tags_template_meta_description_' . $language_info['language_id']];
			} else {
				$data['meta_tags_template']['meta_description'][$language_info['language_id']] = $this->config->get('meta_tags_template_meta_description_' . $language_info['language_id']);
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/meta_tags_template', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/meta_tags_template')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
