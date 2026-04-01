<?php

declare(strict_types=1);

namespace App\Enums;

enum AttachmentFolderEnum: string
{
    case Documents = 'documents';
    case Photos = 'photos';
    case Homework = 'homework';
    case LearningStories = 'learning_stories';
    case Gallery = 'gallery';
}
