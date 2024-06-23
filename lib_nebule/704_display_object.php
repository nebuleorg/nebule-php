<?php
declare(strict_types=1);
namespace Nebule\Library;

/**
 * Classe DisplayObject
 *      ---
 * Example:
 *  FIXME
 *      ---
 * Usage:
 *  FIXME
 *
 * @author Projet nebule
 * @license GNU GPLv3
 * @copyright Projet nebule
 * @link www.nebule.org
 */
class DisplayObject extends DisplayItem implements DisplayInterface
{
    public function getHTML(): string
    {
        return ''; // TODO
    }

    public static function displayCSS(): void
    {
        ?>

        <style type="text/css">
            /* CSS de la fonction getDisplayObject(). */
            .layoutObject {
                margin: 5px 0 0 5px;
                border: 0;
                background: none;
                display: inline-block;
                vertical-align: top;
            }

            .objectDisplayTiny {
                font-size: 16px;
            }

            .objectDisplaySmall {
                font-size: 32px;
            }

            .objectDisplayMedium {
                font-size: 64px;
            }

            .objectDisplayLarge {
                font-size: 128px;
            }

            .objectDisplayFull {
                font-size: 256px;
            }

            .objectTitle a:link, .objectTitle a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .objectTitle a:hover, .objectTitle a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .objectTitleTiny {
                height: 16px;
                font-size: 16px;
                border: 0;
            }

            .objectTitleSmall {
                height: 32px;
                font-size: 32px;
                border: 0;
            }

            .objectTitleMedium {
                height: 64px;
                font-size: 64px;
                border: 0;
            }

            .objectTitleLarge {
                height: 128px;
                font-size: 128px;
                border: 0;
            }

            .objectTitleFull {
                height: 256px;
                font-size: 256px;
                border: 0;
            }

            .objectTitleText {
                background: rgba(255, 255, 255, 0.5);
            }

            .objectTitleTinyText {
                height: 16px;
                background: none;
            }

            .objectTitleSmallText {
                height: 30px;
                text-align: left;
                padding: 1px 0 1px 1px;
                color: #000000;
            }

            .objectTitleMediumText {
                height: 58px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .objectTitleLargeText {
                height: 122px;
                text-align: left;
                padding: 3px 0 3px 3px;
                color: #000000;
            }

            .objectTitleFullText {
                height: 246px;
                text-align: left;
                padding: 5px 0 5px 5px;
                color: #000000;
            }

            .objectTitleTinyRefs {
                visibility: hidden;
            }

            .objectTitleTinyRefs img {
                visibility: hidden;
            }

            .objectTitleSmallRefs {
                height: 12px;
                line-height: 12px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 9px;
            }

            .objectTitleSmallRefs img {
                height: 12px;
                width: 12px;
            }

            .objectTitleMediumRefs {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
            }

            .objectTitleMediumRefs img {
                height: 16px;
                width: 16px;
            }

            .objectTitleLargeRefs {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 12px;
            }

            .objectTitleLargeRefs img {
                height: 16px;
                width: 16px;
            }

            .objectTitleFullRefs {
                height: 32px;
                line-height: 32px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 20px;
            }

            .objectTitleFullRefs img {
                height: 32px;
                width: 32px;
            }

            .objectTitleTinyName {
                height: 1rem;
                line-height: 1rem;
                font-size: 1rem;
            }

            .objectTitleSmallName {
                height: 16px;
                line-height: 16px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 14px;
            }

            .objectTitleMediumName {
                height: 24px;
                line-height: 24px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 20px;
            }

            .objectTitleLargeName {
                height: 32px;
                line-height: 32px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 28px;
            }

            .objectTitleFullName {
                height: 64px;
                line-height: 64px;
                overflow: hidden;
                white-space: nowrap;
                font-size: 40px;
            }

            .objectTitleID {
                height: 16px;
                font-size: 10px;
                overflow: hidden;
            }

            .objectTitleTinyFlags {
                visibility: hidden;
            }

            .objectTitleTinyFlags img {
                visibility: hidden;
            }

            .objectTitleSmallFlags {
                visibility: hidden;
            }

            .objectTitleSmallFlags img {
                visibility: hidden;
            }

            .objectTitleMediumFlags {
                height: 16px;
                font-size: 16px;
            }

            .objectTitleMediumFlags img {
                height: 16px;
                width: 16px;
                margin: 0 1px 0 0;
                float: left;
            }

            .objectTitleLargeFlags {
                height: 16px;
                font-size: 16px;
            }

            .objectTitleLargeFlags img {
                height: 16px;
                width: 16px;
                margin: 0 2px 0 0;
                float: left;
            }

            .objectTitleFullFlags {
                height: 32px;
                font-size: 32px;
            }

            .objectTitleFullFlags img {
                height: 32px;
                width: 32px;
                margin: 0 4px 0 0;
                float: left;
            }

            .objectTitleIcons img {
                height: 1em;
                width: 1em;
                float: left;
            }

            .objectTitleIconsInline img {
                height: 1em;
                width: 1em;
            }

            .objectTitleIconsApp {
                height: 1em;
                width: 1em;
                float: left;
            }

            .objectTitleIconsApp div {
                overflow: hidden;
                font-size: 12px;
                text-align: left;
                font-weight: normal;
                margin: 3px;
                color: #ffffff;
            }

            .objectTitleIconsAppShortname {
                font-size: 18px;
            }

            .objectTitleIconsAppTitle {
                font-size: 11px;
            }

            .objectTitleText0 {
                margin-left: 0;
            }

            .objectTitleText1 {
                margin-left: 1em;
            }

            .objectTitleText2 {
                margin-left: 2em;
            }

            .objectTitleStatus {
                height: 1em;
                line-height: 1em;
                overflow: hidden;
                white-space: nowrap;
                font-weight: bold;
                text-align: right;
                padding-right: 2px;
            }

            .objectDisplayTinyShort {
            }

            .objectDisplaySmallShort {
                width: 8em;
            }

            .objectDisplayMediumShort {
                width: 6em;
            }

            .objectDisplayLargeShort {
                width: 5em;
            }

            .objectDisplayFullShort {
                width: 4em;
            }

            .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                width: 256px;
            }

            @media screen and (min-width: 320px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 310px;
                }
            }

            @media screen and (min-width: 480px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 470px;
                }
            }

            @media screen and (min-width: 600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 590px;
                }
            }

            @media screen and (min-width: 768px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 758px;
                }
            }

            @media screen and (min-width: 1024px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1014px;
                }
            }

            @media screen and (min-width: 1200px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1190px;
                }
            }

            @media screen and (min-width: 1600px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1590px;
                }
            }

            @media screen and (min-width: 1920px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 1910px;
                }
            }

            @media screen and (min-width: 2048px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2038px;
                }
            }

            @media screen and (min-width: 2400px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 2390px;
                }
            }

            @media screen and (min-width: 3840px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 3830px;
                }
            }

            @media screen and (min-width: 4096px) {
                .objectDisplayTinyLong, .objectDisplaySmallLong, .objectDisplayMediumLong, .objectDisplayLargeLong, .objectDisplayFullLong {
                    width: 4086px;
                }
            }

            .objectContent {
                font-size: 0.8rem;
                border: 0;
                padding: 3px;
                margin: 0;
                color: #000000;
                overflow: auto;
            }

            .objectContentShort {
                width: 378px;
                max-height: 378px;
            }

            .objectContentText {
                background: rgba(255, 255, 255, 0.666);
                text-align: left;
            }

            .objectContentImage {
                background: rgba(255, 255, 255, 0.12);
                text-align: center;
            }

            .objectContentImage img {
                height: auto;
                max-width: 100%;
            }

            .objectFlagOn {
                background: #00ff20;
            }

            .objectTitleMenuContentLayout {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.33);
                z-index: 100;
                display: none;
                font-size: 0;
            }

            .objectTitleMenuContent {
                position: fixed;
                top: 10%;
                left: 10%;
                width: 80%;
                background: rgba(240, 240, 240, 0.5);
                padding: 16px;
            }

            .objectTitleMenuContent .objectTitleTinyLong, .objectTitleMenuContent .objectTitleSmallLong, .objectTitleMenuContent .objectTitleMediumLong, .objectTitleMenuContent .objectTitleLargeLong, .objectTitleMenuContent .objectTitleFullLong {
                width: 100%;
            }

            .objectTitleMenuContent .objectTitleText {
                background: rgba(255, 255, 255, 0.66);
            }

            .objectTitleMenuContentIcons {
                height: 64px;
                width: 128px;
            }

            .objectTitleMenuContentIcons img {
                height: 64px;
                width: 64px;
            }

            .objectMenuContent {
                background: rgba(255, 255, 255, 0.2);
                padding-top: 4px;
            }

            .objectMenuContentMsg {
                background-origin: border-box;
                font-size: 14px;
                text-align: left;
                margin-top: 1px;
                width: 100%;
                overflow: hidden;
                white-space: normal;
                min-height: 16px;
            }

            .objectMenuContentMsg img {
                height: 16px;
                width: 16px;
                margin: 0 2px 0 0;
                float: left;
            }

            .objectMenuContentMsgOK {
                background: #103020;
                color: #ffffff;
            }

            .objectMenuContentMsgWarn {
                background: #ffe080;
                color: #ff8000;
            }

            .objectMenuContentMsgError {
                background: #ffa0a0;
                color: #ff0000;
                font-family: monospace;
            }

            .objectMenuContentMsgInfo {
                background: rgba(0, 0, 0, 0.4);
                color: #ffffff;
            }

            .objectMenuContentMsgID {
                background: rgba(255, 255, 255, 0.4);
                color: #000000;
                font-family: monospace;
                font-size: 9px;
                overflow: hidden;
                white-space: nowrap;
                min-height: 4px;
            }

            .objectMenuContentMsgEmotions {
                background: rgba(255, 255, 255, 0.1);
                color: #000000;
                text-align: center;
                min-height: 24px;
            }

            .objectMenuContentMsgEmotions img {
                height: 24px;
                width: 24px;
                margin: 0 1px 0 3px;
                float: none;
            }

            .objectMenuContentMsgtargetObject {
                background: rgba(0, 0, 0, 0.4);
                font-size: 12px;
                color: #ffffff;
                white-space: nowrap;
            }

            .objectMenuContentMsgtargetObject img {
                height: 16px;
                width: 16px;
                margin: 0;
                float: none;
            }

            .objectMenuContentMsgtargetObject a:link, .objectMenuContentMsgtargetObject a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .objectMenuContentMsgtargetObject a:hover, .objectMenuContentMsgtargetObject a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .objectMenuContentActions {
                margin: 1px 0 0 0;
                min-height: 32px;
                padding-top: 5px;
                background: rgba(255, 255, 255, 0.2);
            }

            .objectMenuContentActionsTinyShort {
            }

            .objectMenuContentActionsSmallShort {
                width: 256px;
            }

            .objectMenuContentActionsMediumShort {
                width: 384px;
            }

            .objectMenuContentActionsLargeShort {
                width: 640px;
            }

            .objectMenuContentActionsFullShort {
                width: 1024px;
            }

            .objectMenuContentActionsTinyLong, .objectMenuContentActionsSmallLong, .objectMenuContentActionsMediumLong, .objectMenuContentActionsLargeLong, .objectMenuContentActionsFullLong {
                width: 100%;
            }

            .objectMenuContentAction {
                height: 64px;
                display: inline-block;
                margin-top: 5px;
                margin-left: 5px;
                text-align: left;
            }

            /* Correction à vérifier */
            .objectMenuContentActionNoJS {
                height: 32px;
                display: inline-block;
                margin-bottom: 1px;
                text-align: left;
            }

            .objectMenuContentActionTinyShort {
            }

            .objectMenuContentActionSmallShort {
                width: 256px;
            }

            .objectMenuContentActionMediumShort {
                width: 384px;
            }

            .objectMenuContentActionLargeShort {
                width: 210px;
                margin-right: 5px;
            }

            .objectMenuContentActionFullShort {
                width: 251px;
                margin-right: 5px;
            }

            .objectMenuContentActionTinyLong, .objectMenuContentActionSmallLong, .objectMenuContentActionMediumLong, .objectMenuContentActionLargeLong, .objectMenuContentActionFullLong {
                width: 251px;
            }

            .objectMenuContentActionSelf {
                background: rgba(255, 255, 255, 0.5);
                color: #000000;
            }

            .objectMenuContentActionSelf a:link, .objectMenuContentActionSelf a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #000000;
            }

            .objectMenuContentActionSelf a:hover, .objectMenuContentActionSelf a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #000000;
            }

            .objectMenuContentActionType {
                background: rgba(0, 0, 0, 0.66);
                color: #ffffff;
            }

            .objectMenuContentActionType a:link, .objectMenuContentActionType a:visited {
                font-weight: bold;
                text-decoration: none;
                color: #ffffff;
            }

            .objectMenuContentActionType a:hover, .objectMenuContentActionType a:active {
                font-weight: bold;
                text-decoration: underline;
                color: #ffffff;
            }

            .objectMenuContentAction-icon, .objectMenuContentAction-iconNoJS {
                float: left;
                margin-right: 5px;
            }

            .objectMenuContentAction-icon img {
                height: 64px;
                width: 64px;
            }

            .objectMenuContentAction-iconNoJS img {
                height: 32px;
                width: 32px;
            }

            .objectMenuContentAction-modname p {
                font-size: 0.7rem;
                font-style: italic;
                font-weight: normal;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-title p {
                font-size: 1.1rem;
                font-weight: bold;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-text p {
                font-size: 0.8rem;
                font-weight: normal;
                overflow: hidden;
                white-space: nowrap;
            }

            .objectMenuContentAction-close {
                height: 1px;
                clear: both;
            }
        </style>
        <?php
    }
}
