<?php declare(strict_types=1);

/**
 * This file is part of MadelineProto.
 * MadelineProto is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * MadelineProto is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU General Public License along with MadelineProto.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Amir Hossein Jafari <amirhosseinjafari8228@gmail.com>
 * @copyright 2016-2025 Amir Hossein Jafari <amirhosseinjafari8228@gmail.com>
 * @license   https://opensource.org/licenses/AGPL-3.0 AGPLv3
 * @link https://docs.madelineproto.xyz MadelineProto documentation
 */

namespace danog\MadelineProto\EventHandler\Delete;

use danog\MadelineProto\EventHandler\Delete;
use danog\MadelineProto\MTProto;

/**
 * Some [scheduled messages](https://core.telegram.org/api/scheduled-messages) were deleted from the schedule queue of a chat.
 */
final class DeleteScheduledMessages extends Delete
{
    /** Peer */
    public readonly int $chatId;

    /** @var list<int> Whether this update indicates that some scheduled messages were sent (not simply deleted from the schedule queue). In this case, the `messages` field will contain the scheduled message IDs for the sent messages (initially returned in [updateNewScheduledMessage](https://docs.madelineproto.xyz/API_docs/constructors/updateNewScheduledMessage.html)), and `sent_messages` will contain the real message IDs for the sent messages. */
    public readonly array $sentMessages;

    /** @internal */
    public function __construct(MTProto $API, array $rawDelete)
    {
        parent::__construct($API, $rawDelete);
        $this->chatId = $API->getIdInternal($rawDelete['peer']);
        $this->chatId = $rawDelete['sent_messages'] ?? [];
    }
}
