# Hostile HashOver

An opinionated fork of HashOver 2.0 with additional features.

**HashOver** is a standalone PHP comment system created by Jacob Barkdull. [Read the original README here](./README_original.md).

Like its parent, Hostile HashOver is free and open source software under the
[GNU Affero General Public License](http://www.gnu.org/licenses/agpl.html).

## Features

Hostile HashOver has all the same features as Hashover 2.0, with additional features designed to alleviate the pain of moderation by enforcing constraints on commenting.

* **All comments are held for moderation by default.** Approve comments at your own pace and discourage bad actors.
* **Limited comments per post.** Each thread has a hard limit of 100 comments to keep discussions from getting out of hand.
* **Limited comment length.** Comments are capped at 1000 characters. Choose your words carefully!
* **Plain text only.** You won't have to worry about link spam or XSS because no markup is allowed!
* **Aggressive rate limiting.** Is someone posting through it? Commenters are limited to two (2) comments within 15 minutes.
* **Automatic thread closure.** Comments on posts automatically close after up to 180 days (configurable).
* **Close individual threads, or close comments sitewide** Protect your sanity by closing threads to new comments whenever you want. Or go nuclear and make comments read-only across your entire site.
* **Hide comments sitewide** Need a vacation? Flip a switch and all comments disappear from your website until you flip it back.
* **Sitewide overview by RSS.** Forget email notifications! Use your favorite feed reader and review comments when you're ready.

All new features are configurable so you have complete control over the commenting experience.

## Requirements and Installation

See the [original README](./README_original.md) for instructions.

## Information and Documentation

[Official HashOver 2.0 Documentation](https://docs.barkdull.org/hashover-v2)
