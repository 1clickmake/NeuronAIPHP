# GitHub 업로드 가이드

이 문서는 AI_PHP 프로젝트를 GitHub에 업로드하는 전체 과정을 단계별로 안내합니다.

---

## ✅ 업로드 전 체크리스트

### 1단계: 필수 파일 확인 ✅
다음 파일들이 생성되었는지 확인하세요:

- [x] `.gitignore` - 민감한 파일 제외
- [x] `.env.example` - 환경 변수 템플릿
- [x] `README.md` - 프로젝트 소개
- [x] `LICENSE` - MIT 라이선스
- [x] `SECURITY.md` - 보안 가이드
- [x] `REFACTOR_PLAN.md` - 보안 감사 문서

### 2단계: 민감한 정보 제거 🔒

**중요: GitHub에 올리기 전에 반드시 확인하세요!**

#### ✅ 제거해야 할 것들:
- [ ] `.env` 파일이 `.gitignore`에 포함되었는지 확인
- [ ] 실제 데이터베이스 정보가 코드에 하드코딩되지 않았는지 확인
- [ ] API 키, 비밀번호, 토큰 등이 코드에 없는지 확인
- [ ] `public/data/` 폴더의 실제 업로드 파일들 (`.htaccess`는 유지)
- [ ] 개인 정보가 포함된 데이터베이스 백업 파일

#### 🔍 확인 방법:
```bash
# .env 파일이 제외되는지 확인
git check-ignore .env
# 결과가 ".env"이면 정상

# 민감한 정보 검색
grep -r "password" --exclude-dir=vendor --exclude-dir=.git .
grep -r "api_key" --exclude-dir=vendor --exclude-dir=.git .
```

### 3단계: 프로젝트 정보 수정 ✏️

다음 파일들에서 플레이스홀더를 실제 정보로 교체하세요:

#### `README.md`:
```markdown
# 수정할 항목:
- GitHub 저장소 URL (yourusername/ai_php)
- 프로젝트 관리자 이름
- 이메일 주소
- GitHub Issues 링크
- 뱃지 URL
```

#### `LICENSE`:
```markdown
# 수정할 항목:
- Copyright (c) 2026 [Your Name]
```

#### `project.txt`:
```plaintext
# 수정할 항목 (선택사항):
- 프로젝트 관리자: [관리자명]
- 이메일: [이메일 주소]
```

---

## 🚀 GitHub 업로드 절차

### 방법 1: GitHub Desktop 사용 (초보자 추천)

1. **GitHub Desktop 다운로드**
   - https://desktop.github.com/ 에서 다운로드 및 설치

2. **저장소 생성**
   - GitHub Desktop 실행
   - File → New Repository...
   - Name: `ai_php` 입력
   - Local Path: 프로젝트 폴더 선택
   - "Create Repository" 클릭

3. **파일 커밋**
   - 변경사항 확인 (좌측 패널)
   - Summary: "Initial commit" 입력
   - "Commit to main" 클릭

4. **GitHub에 푸시**
   - "Publish repository" 클릭
   - Keep this code private 체크 해제 (공개 저장소)
   - "Publish Repository" 클릭

---

### 방법 2: 명령줄 사용 (고급 사용자)

#### Step 1: Git 초기화
```bash
cd c:\Users\hades708\OneDrive\바탕 화면\ai_php

# Git 초기화
git init

# 모든 파일 스테이징
git add .

# 초기 커밋
git commit -m "Initial commit: AI_PHP Board System v1.0"
```

#### Step 2: GitHub 저장소 생성
1. https://github.com/new 접속
2. Repository name: `ai_php` 입력
3. Description: "Modern PHP Board System with Advanced Security"
4. Public 선택
5. **Initialize this repository 옵션들을 체크하지 마세요!**
6. "Create repository" 클릭

#### Step 3: 원격 저장소 연결 및 푸시
```bash
# GitHub 저장소 URL을 복사하여 사용
git remote add origin git@github.com:1clickmake/NeuronAIPHP.git

# 기본 브랜치 이름 설정
git branch -M main

# 푸시
git push -u origin main
```

---

## 🎯 업로드 후 설정

### 1. Repository 설정

#### About 섹션:
- Description: "Modern PHP board system with MVC architecture and enterprise-grade security"
- Website: (배포 URL이 있다면)
- Topics 추가:
  ```
  php, board-system, mvc, security, owasp, bootstrap, mysql, 
  cms, forum, file-upload, csrf-protection, xss-prevention
  ```

#### Settings:
- **General**:
  - Features: 
    - ✅ Issues
    - ✅ Wiki (선택)
    - ✅ Discussions (선택)

- **Security**:
  - Security alerts 활성화
  - Dependabot alerts 활성화

### 2. 브랜치 보호 규칙 (선택사항)

Settings → Branches → Add rule:
- Branch name pattern: `main`
- ✅ Require pull request reviews before merging
- ✅ Require status checks to pass

### 3. README 뱃지 업데이트

저장소 생성 후 README.md의 뱃지 URL을 실제 저장소로 변경:

```markdown
변경 전:
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

변경 후:
[![License](https://img.shields.io/github/license/1clickmake/NeuronAIPHP)](LICENSE)
```

---

## 📝 커밋 메시지 가이드

향후 변경사항을 커밋할 때 다음 형식을 권장합니다:

```
<type>: <subject>

<body>

<footer>
```

### Type 종류:
- `feat`: 새로운 기능
- `fix`: 버그 수정
- `docs`: 문서 변경
- `style`: 코드 포맷팅 (기능 변경 없음)
- `refactor`: 코드 리팩토링
- `perf`: 성능 개선
- `test`: 테스트 추가/수정
- `chore`: 빌드/설정 변경
- `security`: 보안 개선

### 예시:
```bash
git commit -m "feat: Add email verification for user registration"
git commit -m "fix: Resolve XSS vulnerability in comment system"
git commit -m "security: Implement CAPTCHA for login form"
git commit -m "docs: Update installation guide in README"
```

---

## 🔒 보안 주의사항

### ⚠️ 절대 GitHub에 올리면 안 되는 것들:

1. **`.env` 파일** - 데이터베이스 정보 포함
2. **실제 사용자 데이터** - `public/data/` 업로드 파일
3. **데이터베이스 백업** - 개인 정보 포함 가능
4. **API 키 / 시크릿 키**
5. **비밀번호 / 토큰**

### ✅ 이미 업로드했다면:

```bash
# 민감한 파일이 실수로 커밋된 경우
git rm --cached .env
git commit -m "Remove .env from repository"
git push

# Git 히스토리에서 완전히 제거 (고급)
# BFG Repo-Cleaner 사용 권장: https://rtyley.github.io/bfg-repo-cleaner/
```

---

## 🎉 완료 후 확인사항

- [ ] GitHub 저장소에서 파일들이 정상적으로 보이는지 확인
- [ ] README.md가 제대로 렌더링되는지 확인
- [ ] Security.md, REFACTOR_PLAN.md 문서가 보이는지 확인
- [ ] 민감한 정보(`.env`, 실제 데이터)가 업로드되지 않았는지 재확인
- [ ] About 섹션에 설명과 Topics이 추가되었는지 확인
- [ ] Issues 탭이 활성화되었는지 확인

---

## 💡 추가 팁

### 1. .gitattributes 파일 추가 (선택사항)

프로젝트 루트에 `.gitattributes` 생성:
```
* text=auto

*.php text eol=lf
*.js text eol=lf
*.css text eol=lf
*.md text eol=lf
*.sql text eol=lf

*.png binary
*.jpg binary
*.jpeg binary
*.gif binary
*.webp binary
```

### 2. GitHub Actions (CI/CD) 설정 (선택사항)

`.github/workflows/php.yml` 생성:
```yaml
name: PHP CI

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
    - name: Install dependencies
      run: composer install
    - name: Check syntax
      run: find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \;
```

### 3. CONTRIBUTING.md 추가 (선택사항)

기여 가이드라인 문서를 추가하여 협업을 촉진할 수 있습니다.

---

## 🆘 문제 해결

### 문제: "fatal: not a git repository"
```bash
cd c:\Users\hades708\OneDrive\바탕 화면\ai_php
git init
```

### 문제: "failed to push some refs"
```bash
git pull origin main --rebase
git push origin main
```

### 문제: Large files 경고
```bash
# 파일 크기 확인
find . -type f -size +50M

# Git LFS 사용 (100MB 이상 파일)
git lfs install
git lfs track "*.zip"
```

---

## 📞 지원

문제가 발생했나요?
- GitHub Issues: https://github.com/1clickmake/NeuronAIPHP/issues
- 이메일: your.email@example.com

---

**작성일**: 2026-02-16  
**최종 업데이트**: 2026-02-16  
**작성자**: AI Assistant
