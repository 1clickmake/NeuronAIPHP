# AI_PHP Board System 🚀

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-7952B3?style=flat&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License](https://img.shields.io/github/license/1clickmake/NeuronAIPHP)](LICENSE)
[![Security](https://img.shields.io/badge/Security-OWASP_Compliant-success)](SECURITY.md)

**AI_PHP Board System**은 현대적인 MVC 아키텍처를 기반으로 한 경량 게시판 시스템입니다. 보안, 성능, 확장성을 고려한 설계로 프로덕션 환경에 즉시 배포 가능합니다.

---

## ✨ 주요 특징

### 🎨 **사용자 경험**
- ✅ **자동 설치 시스템**: 웹 UI를 통한 원클릭 설치
- ✅ **프리미엄 Glassmorphism UI**: 다크 모드 기반 현대적 디자인
- ✅ **반응형 디자인**: 모바일/태블릿/데스크톱 완벽 대응
- ✅ **다중 게시판 스킨**: Basic, Gallery, Blog 스킨 지원

### 🔒 **보안 (PRODUCTION READY)**
- ✅ **CSRF 보호**: 모든 POST 폼에 토큰 적용
- ✅ **XSS 방지**: 헬퍼 함수를 통한 안전한 출력
- ✅ **SQL Injection 방지**: PDO Prepared Statement 사용
- ✅ **파일 업로드 보안**: .htaccess로 스크립트 실행 차단
- ✅ **Rate Limiting**: 로그인 브루트 포스 공격 방지
- ✅ **세션 보안**: httponly, secure, samesite 플래그
- ✅ **IP 접근 제어**: 화이트리스트/블랙리스트
- 📊 **OWASP Top 10 대응**: 8/10 항목 완료

### 🔧 **관리 기능**
- ✅ **사이트 설정**: 로고, 템플릿, 회원가입 설정
- ✅ **회원 관리**: CRUD, 포인트 수동 조정
- ✅ **게시판 관리**: 스킨, 권한, 페이징 커스터마이징
- ✅ **접속자 통계**: Chart.js 기반 시각화
- ✅ **IP 접근 제어**: 실시간 차단/허용
- ✅ **템플릿 빌더**: 클릭 한 번으로 템플릿 생성

### ⚡ **성능 & 확장성**
- ✅ **FastRoute**: 고속 라우팅 시스템
- ✅ **커스텀 페이징**: 게시판별 설정 가능
- ✅ **파일 업로드**: 다중 업로드, 자동 리사이징
- ✅ **포인트 시스템**: 레벨/포인트 관리

---

## 🧩 플러그인 시스템

이 프로젝트는 강력한 플러그인 아키텍처를 지원합니다. 플러그인은 `plugins/` 디렉토리에 위치하며, 독립적으로 동작할 수 있습니다.

### 🤖 Chatbot Plugin (Standalone)
**위치:** `plugins/chatbot/`

Neuron AI 기반의 챗봇 플러그인입니다. 사이트의 콘텐츠(게시글, 페이지, FAQ)를 학습하여 사용자 질문에 자동으로 답변합니다.

- **독립 실행 가능**: AI Manager 플러그인 없이도 단독으로 설치 및 실행이 가능합니다.
- **설정**: 관리자 페이지 > Chatbot Settings에서 API Key 및 동작 방식을 설정할 수 있습니다.
- **주요 기능**:
    - 대화형 인터페이스 (화면 우측 하단 위젯)
    - 사이트 지식 기반 답변 (RAG)
    - 회원/비회원별 질문 제한 설정
    - 대화 로그 저장 및 관리

### 🧠 AI Manager Plugin
**위치:** `plugins/ai-manager/`

사이트 전반에 걸친 AI 기능을 관리합니다. 관리자 페이지에서의 AI 글쓰기 보조 등을 제공합니다.

---

## 🛠️ 기술 스택

| Category | Technology |
|----------|-----------|
| **Backend** | PHP 8.x |
| **Database** | MySQL / MariaDB (PDO) |
| **Frontend** | Bootstrap 5.3.3, jQuery 4.0, Font Awesome 6.0 |
| **Routing** | FastRoute (nikic/fast-route) |
| **Template** | Quill.js (Rich Text Editor), Chart.js |
| **Security** | CSRF Tokens, Bcrypt, PDO, .htaccess |

---

## 🚀 빠른 시작

### 1️⃣ **시스템 요구사항**
- PHP 8.0 이상
- MySQL 5.7 이상 또는 MariaDB 10.2 이상
- Composer
- Apache 또는 Nginx (mod_rewrite 필요)

### 2️⃣ **설치**

```bash
# 저장소 클론
git clone https://github.com/1clickmake/NeuronAIPHP.git
cd NeuronAIPHP

# Composer 의존성 설치
composer install

# 환경 변수 설정
cp .env.example .env
# .env 파일을 열어 데이터베이스 정보 입력

# 개발 서버 실행
php -S localhost:8000 -t public
```

### 3️⃣ **자동 설치**
브라우저에서 `http://localhost:8000` 접속 시 자동으로 설치 화면이 나타납니다.

1. 데이터베이스 정보 입력 (호스트, 사용자, 비밀번호, DB명)
2. 관리자 계정 정보 입력
3. "설치" 버튼 클릭

설치가 완료되면 자동으로 메인 페이지로 이동합니다.

---

## 📂 프로젝트 구조

```
ai_php/
├── app/
│   ├── Controllers/      # MVC 컨트롤러
│   ├── Core/             # 핵심 클래스 (Database, CSRF)
│   └── Services/         # 비즈니스 로직
├── config/
│   └── config.php        # 전역 설정
├── lib/
│   └── common.lib.php    # 공통 함수 라이브러리
├── plugins/              # 확장 플러그인 (Chatbot, AI Manager)
├── public/               # 웹 루트
│   ├── index.php         # 진입점
│   ├── css/              # 스타일시트 (style.css, latest.css 등)
│   ├── js/               # JavaScript (app.js, admin.js 등)
│   └── data/             # 업로드 파일 (.htaccess 보호)
├── views/
│   ├── layout/           # 공통 레이아웃
│   ├── admin/            # 관리자 뷰
│   ├── board/            # 게시판 뷰
│   └── auth/             # 인증 뷰
├── .env.example          # 환경 변수 템플릿
├── .gitignore
├── composer.json
├── setup.sql             # DB 스키마
├── SECURITY.md           # 보안 가이드
└── REFACTOR_PLAN.md      # 보안 감사 및 개선 사항
```

---

## 🔒 보안

이 프로젝트는 **OWASP Top 10** 보안 가이드라인을 준수합니다.

### 구현된 보안 기능
1. ✅ **CSRF 보호** - 모든 폼에 토큰 적용
2. ✅ **XSS 방지** - `e()`, `sanitize_url()` 헬퍼 함수
3. ✅ **SQL Injection 방지** - PDO Prepared Statement
4. ✅ **파일 업로드 보안** - .htaccess 스크립트 차단
5. ✅ **세션 보안** - secure, httponly, samesite 플래그
6. ✅ **Rate Limiting** - 로그인 5회/분 제한
7. ✅ **IP 접근 제어** - 화이트/블랙리스트
8. ✅ **비밀번호 보안** - Bcrypt 해싱, 변경 시 확인

자세한 내용은 [SECURITY.md](SECURITY.md)를 참조하세요.

---

## 📖 문서

- **[SECURITY.md](SECURITY.md)** - 보안 구현 가이드 및 Best Practices
- **[REFACTOR_PLAN.md](REFACTOR_PLAN.md)** - 보안 감사 및 리팩토링 계획

---

## 🎯 주요 기능

### 사용자 기능
- 회원가입 / 로그인 / 로그아웃
- 게시글 작성 / 수정 / 삭제
- 답글 작성 (트리 구조)
- 댓글 작성 / 삭제
- 파일 첨부 (다중 업로드)
- 검색 기능
- 마이페이지

### 관리자 기능
- 대시보드 (통계)
- 사이트 설정 관리
- 회원 관리 (CRUD, 포인트 조정)
- 게시판 그룹/게시판 관리
- 정적 페이지 관리
- 접속자 통계 (Chart.js)
- IP 접근 제어 (화이트/블랙리스트)
- 템플릿 빌더

---

## 🤝 기여하기

기여는 언제나 환영합니다!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request at https://github.com/1clickmake/NeuronAIPHP/pulls

---

## 🐛 버그 리포트

버그를 발견하셨나요? [Issues](https://github.com/1clickmake/NeuronAIPHP/issues)에 등록해주세요.

---

## 📄 라이선스

이 프로젝트는 MIT 라이선스 하에 배포됩니다. 자세한 내용은 [LICENSE](LICENSE) 파일을 참조하세요.

---

## 🙏 감사의 말

- [Neuron AI](https://www.neuron-ai.dev/) - AI 기반 코드 개발 지원
- [FastRoute](https://github.com/nikic/FastRoute) - 고속 라우팅
- [Bootstrap](https://getbootstrap.com/) - UI 프레임워크
- [Font Awesome](https://fontawesome.com/) - 아이콘
- [Quill.js](https://quilljs.com/) - 리치 텍스트 에디터
- [Chart.js](https://www.chartjs.org/) - 차트 라이브러리

---

## 📧 연락처

GitHub: [@1clickmake](https://github.com/1clickmake)  
저장소: [NeuronAIPHP](https://github.com/1clickmake/NeuronAIPHP)  
개발 기간: 2025-12-30 ~ 진행 중

---

## 📊 프로젝트 현황

- **버전**: 1.0.0
- **개발 상태**: Production Ready ✅
- **보안 상태**: OWASP Compliant (8/10)
- **마지막 업데이트**: 2026-02-16

---

<div align="center">

**⭐ 이 프로젝트가 마음에 드셨다면 Star를 눌러주세요! ⭐**

Made with ❤️ by [@1clickmake](https://github.com/1clickmake)

</div>
