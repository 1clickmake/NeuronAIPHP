# 🎨 템플릿 색상 테마 가이드

## 📋 템플릿 목록 및 색상 테마

### 1. **Basic** (밝은 회색 테마)
- **Primary Color**: `#64748b` (Slate 500)
- **Background**: `#f8fafc` (Slate 50 - 밝은 회색)
- **특징**: 깔끔한 화이트 배경, 차분한 회색 포인트
- **적합한 용도**: 기업 사이트, 공식 문서, 포트폴리오

### 2. **Breeze** (차분한 청록색)
- **Primary Color**: `#0d9488` (Teal 600)
- **Gradient**: `#0d9488` → `#2563eb` (Teal to Blue)
- **Background**: 다크 그라데이션
- **특징**: 차분하고 전문적인 느낌, 물결 효과
- **적합한 용도**: 기술 블로그, 스타트업, 전문 서비스

### 3. **Dark** (프리미엄 다크 회색)
- **Primary Color**: `#71717a` (Zinc 500)
- **Background**: 다크 그라데이션 (`#18181b` → `#09090b`)
- **특징**: 클래식한 회색 다크 테마, 눈의 피로 감소
- **적합한 용도**: 게임 커뮤니티, 개발자 포럼, 크리에이티브 작업

### 4. **Corona** (따뜻한 진한 오렌지)
- **Primary Color**: `#ea580c` (Orange 600)
- **Gradient**: `#f59e0b` → `#ea580c` → `#b91c1c` (Amber to Orange to Red)
- **Background**: 다크 퍼플 그라데이션
- **특징**: 태양 코로나 효과, 따뜻하고 에너지 넘치는 느낌
- **적합한 용도**: 이벤트 사이트, 마케팅, 열정적인 커뮤니티

### 5. **Green** (자연 진한 에메랄드)
- **Primary Color**: `#059669` (Emerald 600)
- **Gradient**: `#65a30d` → `#059669` → `#047857` (Lime to Emerald)
- **Background**: 다크 그린 그라데이션
- **특징**: 자연스럽고 친환경적인 느낌, 유기적인 애니메이션
- **적합한 용도**: 환경 단체, 건강/웰빙, 자연 관련 사이트

---

## 🔧 템플릿 변경 방법

### 관리자 페이지에서 변경:
1. 로그인 후 `/admin` 접속
2. **Site Configuration** 메뉴 클릭
3. **Template** 섹션에서 원하는 템플릿 선택
4. **Save Configuration** 버튼 클릭

### 데이터베이스에서 직접 변경:
```sql
UPDATE config SET template = 'breeze' WHERE id = 1;
-- 옵션: basic, breeze, dark, corona, green
```

---

## 🎯 게시판 스킨 색상 적용

게시판 스킨(basic, blog, gallery)은 **자동으로 선택된 템플릿의 색상을 따릅니다**.

### 색상 변수 사용:
- `var(--primary)` - 메인 색상
- `var(--primary-dark)` - 어두운 메인 색상
- `var(--primary-glow)` - 글로우 효과
- `var(--text-main)` - 메인 텍스트 색상
- `var(--text-muted)` - 보조 텍스트 색상
- `var(--glass-border)` - 테두리 색상
- `var(--card-bg)` - 카드 배경색

### 예시:
```css
/* 템플릿이 breeze일 때 */
--primary: #06b6d4;  /* Cyan */

/* 템플릿이 corona일 때 */
--primary: #f97316;  /* Orange */
```

---

## 📝 테스트 방법

### 1. 각 템플릿 확인:
1. 관리자 페이지에서 템플릿 변경
2. 메인 페이지(`/`) 확인
3. 게시판 페이지 확인 (예: `/board/free`)
4. 게시글 작성/보기 페이지 확인

### 2. 색상 일관성 체크:
- [ ] 네비게이션 바 색상
- [ ] 버튼 색상 (Primary, Secondary)
- [ ] 카드/글래스 효과
- [ ] 게시판 테이블 색상
- [ ] 댓글 섹션 색상
- [ ] 페이지네이션 색상

### 3. 반응형 테스트:
- [ ] 데스크톱 (1920px)
- [ ] 태블릿 (768px)
- [ ] 모바일 (375px)

---

## 🛠️ 커스터마이징

### 새 템플릿 생성:
1. 관리자 페이지 → Site Configuration
2. "Create New Template" 섹션에서 이름 입력
3. "Create Template" 버튼 클릭
4. 생성된 폴더에서 CSS 수정:
   - `public/assets/templates/[name]/css/style.css`

### 색상 변경:
```css
:root {
    --primary: #YOUR_COLOR !important;
    --primary-dark: #YOUR_DARK_COLOR !important;
    --primary-glow: rgba(YOUR_RGB, 0.3);
}
```

---

## 📊 현재 상태

✅ 5개 템플릿 준비 완료
✅ 게시판 스킨 자동 색상 적용
✅ CSS 변수 시스템 구축
✅ 관리자 페이지 템플릿 선택 기능

---

## 🚀 다음 단계

1. 브라우저에서 `http://localhost:8000/admin` 접속
2. 로그인 (admin / admin123)
3. Site Configuration에서 템플릿 변경 테스트
4. 각 템플릿의 게시판 색상 확인
5. 필요한 부분 수정 요청

---

**작성일**: 2026-02-16
**버전**: 1.0.0
