# Changelog

## Unreleased

## 0.1.4 - 2026-08-24

- Pin SDWebImage to the exact reviewed `5.21.7` release so PAM Native's
  production supply-chain audit can certify the plugin without allowing a
  different minor version to resolve under the same descriptor.

## 0.1.3 - 2026-08-24

- Bind SDWebImage progress and completion callbacks explicitly in the public
  iOS aggregate build.
- Reject unsafe relative sandbox paths before they reach native decoders.
- Add the standard PAM ecosystem publication-compatibility gate.

## 0.1.2 - 2026-08-24

- Add immutable native layout styling.
- Validate a clean Android API 36 launch, remote decode, cover resize and
  non-black rendered frame in a standalone PAM consumer.

## 0.1.1 - 2026-08-24

- Align Coil with PAM Native's Kotlin toolchain and certify an Android release
  consumer build for arm64-v8a and x86_64.
- Enforce every cache policy on iOS and honor native crossfade duration.
