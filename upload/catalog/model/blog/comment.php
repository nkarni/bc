<?php
class ModelBlogComment extends Model {
    public function addComment($article_id, $data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_comment SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', article_id = '" . (int)$article_id . "', text = '" . $this->db->escape($data['text']) . "', status = '" . ($this->config->get('easy_blog_comment_config_approve')?"0":"1") . "', date_modified = NOW()");

        $comment_id = $this->db->getLastId();

        if ($this->config->get('easy_blog_comment_config_mail')) {
            $this->load->language('mail/easy_blog_comment');
            $this->load->model('blog/article');

            $article_info = $this->model_blog_article->getArticle($article_id);

            $subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

            $message  = $this->language->get('text_waiting') . "\n";
            $message .= sprintf($this->language->get('text_article'), html_entity_decode($article_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
            $message .= sprintf($this->language->get('text_commentator'), html_entity_decode($data['name'], ENT_QUOTES, 'UTF-8')) . "\n";
            $message .= $this->language->get('text_comment') . "\n";
            $message .= html_entity_decode($data['text'], ENT_QUOTES, 'UTF-8') . "\n\n";

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject($subject);
            $mail->setText($message);
            $mail->send();

            // Send to additional alert emails
            $emails = explode(',', $this->config->get('config_mail_alert'));

            foreach ($emails as $email) {
                if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }
    }

    public function getCommentsByArticleId($article_id, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $query = $this->db->query("SELECT c.comment_id, c.author, c.text, a.article_id, ad.name, c.date_modified FROM " . DB_PREFIX . "easy_blog_comment c LEFT JOIN " . DB_PREFIX . "easy_blog_article a ON (c.article_id = a.article_id) LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND a.status = '1' AND c.status = '1' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.date_modified " . $this->config->get('easy_blog_comment_order') . " LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalCommentsByArticleId($article_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "easy_blog_comment c LEFT JOIN " . DB_PREFIX . "easy_blog_article a ON (c.article_id = a.article_id) LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND a.status = '1' AND c.status = '1' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }
}