# Skills Quick Reference Guide

## 🎯 20 Skills Installed for Production-Ready Marketplace

---

## Backend Skills (12)

### Testing & Quality
1. **/test-writer** — Generate PHPUnit tests
   - Use: `@test-writer OrderController::store with concurrent attempts`
   
2. **/wallet-test-suite** — Financial operation tests
   - Use: `@wallet-test-suite test TOCTOU protection`

3. **/security-test** — Security vulnerability tests
   - Use: `@security-test test all auth endpoints`

4. **/buyer-flow-validator** — Complete user journey tests
   - Use: `@buyer-flow-validator test registration to purchase`

5. **/seller-onboarding** — Seller validation
   - Use: `@seller-onboarding validate new seller setup`

### Performance & Operations
6. **/perf-audit** — Performance bottleneck identification
   - Use: `@perf-audit analyze product listing queries`

7. **/db-migration** — Safe database changes
   - Use: `@db-migration add indexes to orders table`

8. **/health-check** — Application monitoring
   - Use: `@health-check create monitoring endpoint`

9. **/log-analyzer** — Error log parsing
   - Use: `@log-analyzer analyze last 24 hours errors`

10. **/deploy-check** — Deployment validation
    - Use: `@deploy-check validate production readiness`

### Documentation
11. **/api-doc** — OpenAPI documentation
    - Use: `@api-doc generate docs for all API routes`

12. **/commit** — Gitmoji convention
    - Use: `/commit` (creates commit with proper format)

---

## Frontend Skills (8)

### Design & UI
13. **/ui-reviewer** — Design consistency auditing
    - Use: `@ui-reviewer audit resources/views/home.blade.php`

14. **/frontend-design** — Design system implementation
    - Use: `@frontend-design create button component`

15. **/design-system** — Component library management
    - Use: `@design-system add card component`

16. **/blade-optimizer** — Template performance
    - Use: `@blade-optimizer optimize product listing`

### User Experience
17. **/responsive-check** — Mobile-first design
    - Use: `@responsive-check validate all breakpoints`

18. **/accessibility-auditor** — WCAG 2.1 compliance
    - Use: `@accessibility-auditor check forms`

19. **/ux-flow-optimizer** — Conversion optimization
    - Use: `@ux-flow-optimizer optimize checkout flow`

20. **/pwa-setup** — Progressive Web App
    - Use: `@pwa-setup configure offline support`

---

## 🚀 Common Workflows

### Workflow 1: Launch New Feature
```bash
# 1. Create feature
@frontend-design create product comparison component

# 2. Test it
@buyer-flow-validator test comparison flow

# 3. Check accessibility
@accessibility-auditor check comparison page

# 4. Optimize performance
@blade-optimizer optimize comparison template

# 5. Document it
@api-doc add comparison endpoints
```

### Workflow 2: Fix Conversion Issue
```bash
# 1. Analyze flow
@ux-flow-optimizer analyze checkout abandonment

# 2. Test current behavior
@buyer-flow-validator test checkout completion

# 3. Implement fix
@frontend-design improve checkout UI

# 4. Validate improvement
@perf-audit measure checkout performance

# 5. Deploy
@deploy-check validate deployment
```

### Workflow 3: Security Hardening
```bash
# 1. Audit security
@security-test test all endpoints

# 2. Fix issues
@test-writer add security tests

# 3. Verify fixes
@security-test re-test fixed endpoints

# 4. Monitor
@health-check add security monitoring

# 5. Document
@api-doc update security documentation
```

### Workflow 4: Performance Optimization
```bash
# 1. Identify bottleneck
@perf-audit analyze slow queries

# 2. Optimize database
@db-migration add missing indexes

# 3. Add caching
@blade-optimizer cache expensive queries

# 4. Optimize frontend
@responsive-check optimize images

# 5. Verify improvement
@perf-audit measure improvement
```

### Workflow 5: Mobile Launch
```bash
# 1. Check responsiveness
@responsive-check test all pages

# 2. Optimize UX
@ux-flow-optimizer optimize mobile flows

# 3. Add PWA
@pwa-setup configure service worker

# 4. Test accessibility
@accessibility-auditor check mobile

# 5. Monitor
@health-check add mobile metrics
```

---

## 📊 Skill Usage Priority

### Critical (Use Daily)
- **test-writer** — Every code change
- **security-test** — Before deployment
- **perf-audit** — Weekly optimization
- **deploy-check** — Every deployment

### High (Use Weekly)
- **ui-reviewer** — Design reviews
- **responsive-check** — Mobile testing
- **ux-flow-optimizer** — Conversion analysis
- **health-check** — System monitoring

### Medium (Use Monthly)
- **design-system** — Component updates
- **blade-optimizer** — Template cleanup
- **db-migration** — Schema changes
- **api-doc** — Documentation updates

### Low (Use Quarterly)
- **pwa-setup** — PWA updates
- **log-analyzer** — Log analysis
- **accessibility-auditor** — WCAG audits
- **seller-onboarding** — Process reviews

---

## 🎯 Quick Wins

### Today
1. Run `@security-test` on all controllers
2. Run `@perf-audit` on product listing
3. Run `@ui-reviewer` on homepage
4. Run `@responsive-check` on mobile

### This Week
1. Implement `@frontend-design` components
2. Add `@health-check` endpoint
3. Optimize with `@blade-optimizer`
4. Test with `@buyer-flow-validator`

### This Month
1. Complete `@design-system` components
2. Configure `@pwa-setup`
3. Document with `@api-doc`
4. Automate with `@deploy-check`

---

## 📚 Documentation

- **PRODUCTION-READINESS-PLAN.md** — 4-week roadmap
- **Each skill's SKILL.md** — Detailed instructions
- **GitHub Issues** — Track progress
- **This file** — Quick reference

---

## 💡 Tips

1. **Start with security** — Always run security tests first
2. **Test early, test often** — Use test-writer for every feature
3. **Design system first** — Build components before pages
4. **Mobile first** — Always use responsive-check
5. **Measure everything** — Use perf-audit to track improvements

---

**Total Skills:** 20
**Coverage:** Backend + Frontend + DevOps + UX
**Goal:** Production-ready marketplace in 4 weeks

Generated by Claude Code | Ready to make your marketplace shine! ✨
