# Managing thewoolleyweb.com

## Website and style

- This repository serves https://thewoolleyweb.com/ through GitHub Pages. The publishing source is the root of the `master` branch on `origin` (`thewoolleyman/thewoolleyman`). `CNAME` sets the custom domain.
- Edit the maintained HTML directly. `index.html` is the homepage; `default.css` and `css/` contain the existing styles. GitHub Pages also processes the site with Jekyll using `_config.yml`.
- Preserve the style of the page being edited: its typography, colors, spacing, navigation, markup conventions, and relative links. Reuse existing patterns. Do not redesign the site unless asked.
- Preserve historical pages, media, filenames, and published URLs. Check inbound references before moving or deleting files. Keep the canonical and versioned guide copies consistent when the requested change applies to both.
- `src/`, the Webgen configuration, and the Ruby/SVN deployment task in `Rakefile` are historical artifacts. Do not run `rake publish_website` or regenerate the site with Webgen as part of normal maintenance.
- Inspect the working tree first, preserve unrelated work, and stage only files belonging to the requested change.

## Check links across the whole site

- For each website change, check all published pages for broken links, including `.html`, `.htm`, `.shtml`, Markdown pages, and pages under historical directories. Do not limit the scan to the homepage or edited files. Include shared layouts and styles when collecting asset references; distinguish generator inputs under `src/` from published pages.
- Check navigation links, downloads, images, scripts, stylesheets, embedded media, CSS asset URLs, and internal fragment identifiers. Resolve relative URLs against the referring page and root-relative URLs against the site root. Decode URL escapes correctly, preserve filename case, and account for directory indexes, Jekyll output paths, and redirects.
- Check external HTTP(S) destinations with bounded concurrency and timeouts, following redirects. If HEAD is refused, try GET. Treat 404/410 as broken; report authentication requirements, 403/429 responses, timeouts, and network failures as unverified rather than automatically declaring the destination dead. Do not send requests to `mailto:`, `tel:`, `javascript:`, or `data:` URLs.
- Record failures with the referring page, original URL, and observed result. Fix regressions introduced by the current work. Report pre-existing failures separately; preserve archival content and avoid broad unrelated cleanup.
- After publication, verify changed pages and their destinations on the live custom domain. A local file existing is not proof that its public URL works.

## Commit, push, and verify live publication

Always carry authorized website work through commit, push, and verified live publication, unless the user explicitly requests otherwise. Do not stop after making local edits or after a successful push.

1. Review the diff, run `git diff --check`, perform the site-wide link check, and verify the requested behavior. For visual changes, inspect the affected pages in a browser at desktop and narrow widths.
2. Fetch `origin` and check for upstream changes. Integrate safely without overwriting unrelated work; do not force-push. Commit the relevant files with a descriptive message and push to the publishing branch, normally `git push origin master`. If working on another branch, ensure the authorized change actually reaches `master`; pushing a feature branch alone does not publish it.
3. Record the pushed SHA and verify that GitHub Pages builds and deploys that exact commit successfully. Useful commands:

   ```sh
   git rev-parse HEAD
   gh api repos/thewoolleyman/thewoolleyman/pages \
     --jq '{status,cname,source,build_type}'
   gh run list --repo thewoolleyman/thewoolleyman --limit 5 \
     --json databaseId,headSha,status,conclusion,url
   gh run view RUN_ID --repo thewoolleyman/thewoolleyman \
     --json headSha,status,conclusion,url
   gh api repos/thewoolleyman/thewoolleyman/pages/builds/latest \
     --jq '{status,commit,error}'
   ```

   Confirm the workflow's SHA, successful deployment, and Pages build status; an older successful run is not sufficient. If the build fails, inspect its logs, fix the cause, and repeat the commit/push/verification cycle.
4. Request https://thewoolleyweb.com/ and every changed public page or asset, following redirects and checking the response content as well as status. Verify the actual change appears live, and follow affected links through to their destinations. For documentation-only changes, verify deployment and that the existing homepage still works. Use bounded polling while deployment or cache updates are pending.
5. Cloudflare may rewrite email links and inject an email-decoding script into live HTML. Account for those transformations when comparing source with published content. If one HTTP client is blocked, retry with `curl` or a browser; never bypass TLS verification. A useful initial check is `curl --fail --silent --show-error --location --max-time 30 https://thewoolleyweb.com/`.
6. Finish with the commit SHA, push/build/deployment result, live URLs verified, and any unresolved link failures or verification limits. Clearly state a deployment or verification blocker if one remains; do not claim publication solely from configuration, a push, or a build starting.
