---
name: commit
description: Create a git commit for the staged/relevant changes following this repo's gitmoji + type convention. Use when the user asks to commit, save work, or says "commit this".
disable-model-invocation: true
---

# Commit Skill

Create a commit following this repository's established message convention.

## Convention (from git history)

Format: `<emoji><type>: <imperative lowercase summary>`

| Emoji | Type | Use for |
|-------|------|---------|
| ✨ | feat | New feature or functionality |
| 🩹 | fix | Bug fix |
| 📝 | change | Refactors, cleanup, minor UI/UX changes, docs |
| 🎨 | style | Code style/formatting only |
| ⚡️ | perf | Performance improvement |
| ♻️ | refactor | Structural refactoring |
| 🔥 | chore | Removing code/files |
| 🚑 | fix | Critical hotfix |

Examples from this repo's history:
- `✨feat: implement user follow management and enhance review system with verified purchase tracking`
- `🩹fix: fix a mistake`
- `📝change: code cleanup`
- `📝change: minor changes in ui`

## Steps

1. Run `git status` and `git diff` (staged and unstaged) to understand the change. Read changed files if the summary isn't obvious.
2. Pick the matching emoji+type for the dominant change. Multiple logical changes → ask the user whether to split into multiple commits.
3. Write a one-line summary in English, lowercase, imperative, no trailing period. Mention the main feature/area (e.g. "wallet", "admin users", "reviews").
4. Stage the relevant files: `git add <files>` (never `git add -A` blindly — exclude scratch files).
5. Commit with the single-line message. End with the attribution line:
   `Co-Authored-By: Claude Code <noreply@anthropic.com>`
6. Show the user the commit hash and summary. Do not push unless asked.
