SET FOREIGN_KEY_CHECKS = 0;

-- 사용자 정보 테이블
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '고유 식별 ID',
    `user_id` VARCHAR(255) NOT NULL UNIQUE COMMENT '사용자 아이디',
    `username` VARCHAR(50) NOT NULL UNIQUE COMMENT '사용자 이름',
    `password` VARCHAR(255) NOT NULL COMMENT '암호화된 비밀번호',
    `email` VARCHAR(100) NOT NULL UNIQUE COMMENT '이메일 주소',
    `role` ENUM('user', 'admin') DEFAULT 'user' COMMENT '사용자 권한',
    `point` INT(11) DEFAULT 0 COMMENT '포인트',
    `level` INT(11) DEFAULT 1 COMMENT '레벨',
    `country` VARCHAR(50) DEFAULT 'Unknown' COMMENT '접속 국가',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '가입 일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '사용자 정보 테이블';

-- 사이트 정보 설정 테이블
DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
    `id` INT(11) PRIMARY KEY DEFAULT 1 COMMENT '고유 ID',
    `site_name` VARCHAR(100) DEFAULT 'Neuron AI PHP' COMMENT '사이트 명',
    `company_name` VARCHAR(100) DEFAULT '' COMMENT '회사 명',
    `company_owner` VARCHAR(50) DEFAULT '' COMMENT '대표자 명',
    `company_license_num` VARCHAR(50) DEFAULT '' COMMENT '사업자 등록번호',
    `company_tel` VARCHAR(50) DEFAULT '' COMMENT '회사 전화번호',
    `company_email` VARCHAR(100) DEFAULT '' COMMENT '회사 이메일',
    `company_address` VARCHAR(255) DEFAULT '' COMMENT '회사 주소',
    `company_info` TEXT COMMENT '회사 소개내용',
    `logo_type` ENUM('text', 'image') DEFAULT 'text' COMMENT '로고 타입',
    `logo_text` VARCHAR(100) DEFAULT '' COMMENT '로고 텍스트',
    `logo_image` VARCHAR(255) DEFAULT '' COMMENT '로고 이미지 경로',
    `template` VARCHAR(50) DEFAULT 'basic' COMMENT '사이트 템플릿',
    `join_point` INT(11) DEFAULT 0 COMMENT '가입시 지급 포인트',
    `join_level` INT(11) DEFAULT 1 COMMENT '가입시 부여 레벨',
    `allowed_ips` TEXT COMMENT '접속 허용 IP 목록',
    `blocked_ips` TEXT COMMENT '접속 차단 IP 목록',
    `faq_category` VARCHAR(255) DEFAULT '회원|포인트|게시판|기타' COMMENT 'FAQ 카테고리',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정 일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '사이트 정보 설정 테이블';

-- 독립 페이지 관리 테이블
DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '페이지 고유 ID',
    `title` VARCHAR(255) NOT NULL COMMENT '페이지 제목',
    `slug` VARCHAR(100) NOT NULL UNIQUE COMMENT '페이지 URL 슬러그',
    `content` LONGTEXT NOT NULL COMMENT '페이지 내용',
    `display_title` TINYINT(1) DEFAULT 1 COMMENT '제목 표시 여부',
    `use_card_style` TINYINT(1) DEFAULT 1 COMMENT '카드 스타일 사용 여부',
    `editor_mode` ENUM('visual', 'html') DEFAULT 'visual' COMMENT '에디터 모드',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정 일시',
    INDEX `idx_slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '독립 페이지 관리 테이블';

-- 기본 독립 페이지 생성
INSERT INTO `pages` (`title`, `slug`, `content`) VALUES 
('About Us', 'about-us', '<p>This is the About Us page.</p>'),
('Terms of Service', 'terms-of-service', '<p>This is the Terms of Service page.</p>'),
('Privacy Policy', 'privacy-policy', '<p>This is the Privacy Policy page.</p>');

-- 기본 설정값 삽입
INSERT IGNORE INTO `config` (`id`, `site_name`) VALUES (1, 'Neuron AI PHP');

-- 게시판 그룹 테이블
DROP TABLE IF EXISTS `board_groups`;
CREATE TABLE IF NOT EXISTS `board_groups` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '그룹 고유 ID',
    `name` VARCHAR(100) NOT NULL COMMENT '그룹 이름',
    `slug` VARCHAR(100) NOT NULL UNIQUE COMMENT '그룹 슬러그',
    `description` TEXT COMMENT '그룹 설명',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    INDEX `idx_slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '게시판 그룹 테이블';

-- 게시판 설정 테이블
DROP TABLE IF EXISTS `boards`;
CREATE TABLE IF NOT EXISTS `boards` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '게시판 고유 ID',
    `group_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '소속 그룹 ID',
    `title` VARCHAR(100) NOT NULL COMMENT '게시판 제목',
    `slug` VARCHAR(100) NOT NULL UNIQUE COMMENT '게시판 슬러그',
    `description` TEXT COMMENT '게시판 설명',
    `skin` VARCHAR(50) DEFAULT 'basic' COMMENT '게시판 스킨',
    `max_replies` INT(11) DEFAULT 3 COMMENT '원글당 최대 답글 개수',
    `level_list` INT(11) DEFAULT 1 COMMENT '목록 접근 레벨',
    `level_view` INT(11) DEFAULT 1 COMMENT '상세보기 접근 레벨',
    `level_write` INT(11) DEFAULT 1 COMMENT '글쓰기 접근 레벨',
    `level_comment` INT(11) DEFAULT 1 COMMENT '댓글 작성 레벨',
    `point_write` INT(11) DEFAULT 0 COMMENT '글작성 포인트',
    `point_view` INT(11) DEFAULT 0 COMMENT '상세보기 포인트(차감시 음수)',
    `point_comment` INT(11) DEFAULT 0 COMMENT '댓글작성 포인트',
    `allow_comments` TINYINT(1) DEFAULT 1 COMMENT '댓글 허용 여부',
    `page_rows` INT(11) DEFAULT 20 COMMENT '페이지당 출력 행수',
    `page_buttons` INT(11) DEFAULT 5 COMMENT '페이징 버튼 수',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    INDEX `idx_group_id` (`group_id`),
    INDEX `idx_slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '게시판 설정 테이블';

-- 기본 게시판 그룹 및 게시판 생성
INSERT INTO `board_groups` (`id`, `name`, `slug`, `description`) VALUES (1, 'community', 'community', 'community group');
INSERT INTO `boards` (`group_id`, `title`, `slug`, `description`, `skin`) VALUES 
(1, 'free board', 'free', 'free board', 'basic'),
(1, 'gallery board', 'gallery', 'gallery board', 'gallery'),
(1, 'blog', 'blog', 'blog', 'blog');

-- 게시글 테이블
DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '게시글 고유 ID',
    `group_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '소속 그룹 ID',
    `board_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '게시판 ID',
    `user_id` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '작성자 ID',
    `parent_id` INT(11) UNSIGNED DEFAULT NULL COMMENT '부모 글 ID',
    `title` VARCHAR(255) NOT NULL COMMENT '게시글 제목',
    `content` TEXT NOT NULL COMMENT '게시글 내용',
    `reply_depth` INT(11) DEFAULT 0 COMMENT '답글 깊이',
    `views` INT(11) DEFAULT 0 COMMENT '조회수',
    `editor_mode` ENUM('visual', 'html') DEFAULT 'visual' COMMENT '에디터 모드',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '작성 일시',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정 일시',
    INDEX `idx_board_id` (`board_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '게시글 테이블';

-- 댓글 테이블
DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '댓글 고유 ID',
    `post_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '게시글 ID',
    `user_id` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '작성자 ID',
    `parent_comment_id` INT(11) UNSIGNED DEFAULT NULL COMMENT '부모 댓글 ID',
    `content` TEXT NOT NULL COMMENT '댓글 내용',
    `reply_depth` INT(11) DEFAULT 0 COMMENT '댓글 깊이',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '작성 일시',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정 일시',
    INDEX `idx_post_id` (`post_id`),
    INDEX `idx_parent_comment_id` (`parent_comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '댓글 테이블';

-- 게시글 첨부 파일 테이블
DROP TABLE IF EXISTS `post_files`;
CREATE TABLE IF NOT EXISTS `post_files` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '파일 고유 ID',
    `post_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '게시글 ID',
    `filename` VARCHAR(255) NOT NULL COMMENT '저장된 파일명',
    `original_name` VARCHAR(255) NOT NULL COMMENT '원본 파일명',
    `filepath` VARCHAR(255) NOT NULL COMMENT '파일 경로',
    `file_size` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '파일 크기',
    `file_type` VARCHAR(100) COMMENT '파일 타입',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '업로드 일시',
    INDEX `idx_post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '게시글 첨부 파일 테이블';

-- 파일 다운로드 기록 테이블
DROP TABLE IF EXISTS `file_downloads`;
CREATE TABLE IF NOT EXISTS `file_downloads` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '다운로드 기록 ID',
    `file_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '파일 ID',
    `user_id` VARCHAR(255) DEFAULT NULL COMMENT '다운로드한 사용자 ID',
    `ip_address` VARCHAR(45) NOT NULL COMMENT '다운로드 IP 주소',
    `download_count` INT(11) DEFAULT 1 COMMENT '다운로드 횟수',
    `last_downloaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '마지막 다운로드 일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '파일 다운로드 기록 테이블';

-- 접속자 로그 테이블
DROP TABLE IF EXISTS `visitor_logs`;
CREATE TABLE IF NOT EXISTS `visitor_logs` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '로그 고유 ID',
    `ip_address` VARCHAR(45) NOT NULL COMMENT 'IP 주소',
    `country` VARCHAR(50) DEFAULT 'Unknown' COMMENT '국가 코드/이름',
    `user_agent` TEXT COMMENT '브라우저 에이전트',
    `referer` TEXT COMMENT '유입 경로',
    `visit_date` DATE NOT NULL COMMENT '접속 날짜',
    `visit_time` TIME NOT NULL COMMENT '접속 시간',
    `last_active_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '마지막 활성 시간',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '기록 일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '접속자 로그 테이블';

CREATE INDEX idx_visit_date ON visitor_logs(visit_date);
CREATE INDEX idx_ip_date ON visitor_logs(ip_address, visit_date);

-- 포인트 이력 테이블
DROP TABLE IF EXISTS `point_log`;
CREATE TABLE IF NOT EXISTS `point_log` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '고유 ID',
    `user_id` VARCHAR(255) NOT NULL COMMENT '사용자 아이디',
    `point` INT(11) NOT NULL COMMENT '지급/차감 포인트',
    `rel_msg` VARCHAR(255) DEFAULT '' COMMENT '관련 사유/메시지',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '일시',
    INDEX `idx_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '포인트 이력 테이블';

-- 메일 발송 로그 테이블
DROP TABLE IF EXISTS `mail_logs`;
CREATE TABLE IF NOT EXISTS `mail_logs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '고유 ID',
    `target_info` VARCHAR(255) DEFAULT NULL COMMENT '수신 대상 정보',
    `sender_name` VARCHAR(100) DEFAULT NULL COMMENT '발송자 이름',
    `sender_phone` VARCHAR(50) DEFAULT NULL COMMENT '발송자 연락처',
    `sender_email` VARCHAR(255) DEFAULT NULL COMMENT '발송자 이메일',
    `recipient` LONGTEXT NOT NULL COMMENT '수신 이메일 목록',
    `subject` VARCHAR(255) NOT NULL COMMENT '메일 제목',
    `content` LONGTEXT NOT NULL COMMENT '메일 본문',
    `attachments` TEXT DEFAULT NULL COMMENT '첨부 파일 목록',
    `status` VARCHAR(20) NOT NULL DEFAULT 'success' COMMENT '발송 상태',
    `error_message` TEXT DEFAULT NULL COMMENT '에러 메시지',
    `sent_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '발송 일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '메일 발송 로그 테이블';

-- FAQ 테이블
DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '고유 ID',
    `category` VARCHAR(50) NOT NULL COMMENT '질문 카테고리',
    `question` VARCHAR(255) NOT NULL COMMENT '질문',
    `answer` TEXT NOT NULL COMMENT '답변',
    `display_order` INT(11) DEFAULT 0 COMMENT '출력 순서',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정 일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT 'FAQ 테이블';

SET FOREIGN_KEY_CHECKS = 1;
