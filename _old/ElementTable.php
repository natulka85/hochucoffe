<?
namespace nav\IblockOrm;
use nav\IblockOrm;
use Bitrix\Main;
use Bitrix\Main\Entity;

class ElementTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_iblock_element';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('ELEMENT_ENTITY_ID_FIELD')
                ]
            ),
            new DatetimeField(
                'TIMESTAMP_X',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_TIMESTAMP_X_FIELD')
                ]
            ),
            new IntegerField(
                'MODIFIED_BY',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_MODIFIED_BY_FIELD')
                ]
            ),
            new DatetimeField(
                'DATE_CREATE',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_DATE_CREATE_FIELD')
                ]
            ),
            new IntegerField(
                'CREATED_BY',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_CREATED_BY_FIELD')
                ]
            ),
            new IntegerField(
                'IBLOCK_ID',
                [
                    'default' => 0,
                    'title' => Loc::getMessage('ELEMENT_ENTITY_IBLOCK_ID_FIELD')
                ]
            ),
            new IntegerField(
                'IBLOCK_SECTION_ID',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_IBLOCK_SECTION_ID_FIELD')
                ]
            ),
            new BooleanField(
                'ACTIVE',
                [
                    'values' => array('N', 'Y'),
                    'default' => 'Y',
                    'title' => Loc::getMessage('ELEMENT_ENTITY_ACTIVE_FIELD')
                ]
            ),
            new DatetimeField(
                'ACTIVE_FROM',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_ACTIVE_FROM_FIELD')
                ]
            ),
            new DatetimeField(
                'ACTIVE_TO',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_ACTIVE_TO_FIELD')
                ]
            ),
            new IntegerField(
                'SORT',
                [
                    'default' => 500,
                    'title' => Loc::getMessage('ELEMENT_ENTITY_SORT_FIELD')
                ]
            ),
            new StringField(
                'NAME',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateName'],
                    'title' => Loc::getMessage('ELEMENT_ENTITY_NAME_FIELD')
                ]
            ),
            new IntegerField(
                'PREVIEW_PICTURE',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_PREVIEW_PICTURE_FIELD')
                ]
            ),
            new TextField(
                'PREVIEW_TEXT',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_PREVIEW_TEXT_FIELD')
                ]
            ),
            new StringField(
                'PREVIEW_TEXT_TYPE',
                [
                    'values' => array('text', 'html'),
                    'default' => 'text',
                    'title' => Loc::getMessage('ELEMENT_ENTITY_PREVIEW_TEXT_TYPE_FIELD')
                ]
            ),
            new IntegerField(
                'DETAIL_PICTURE',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_DETAIL_PICTURE_FIELD')
                ]
            ),
            new TextField(
                'DETAIL_TEXT',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_DETAIL_TEXT_FIELD')
                ]
            ),
            new StringField(
                'DETAIL_TEXT_TYPE',
                [
                    'values' => array('text', 'html'),
                    'default' => 'text',
                    'title' => Loc::getMessage('ELEMENT_ENTITY_DETAIL_TEXT_TYPE_FIELD')
                ]
            ),
            new TextField(
                'SEARCHABLE_CONTENT',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_SEARCHABLE_CONTENT_FIELD')
                ]
            ),
            new IntegerField(
                'WF_STATUS_ID',
                [
                    'default' => 1,
                    'title' => Loc::getMessage('ELEMENT_ENTITY_WF_STATUS_ID_FIELD')
                ]
            ),
            new IntegerField(
                'WF_PARENT_ELEMENT_ID',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_WF_PARENT_ELEMENT_ID_FIELD')
                ]
            ),
            new StringField(
                'WF_NEW',
                [
                    'validation' => [__CLASS__, 'validateWfNew'],
                    'title' => Loc::getMessage('ELEMENT_ENTITY_WF_NEW_FIELD')
                ]
            ),
            new IntegerField(
                'WF_LOCKED_BY',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_WF_LOCKED_BY_FIELD')
                ]
            ),
            new DatetimeField(
                'WF_DATE_LOCK',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_WF_DATE_LOCK_FIELD')
                ]
            ),
            new TextField(
                'WF_COMMENTS',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_WF_COMMENTS_FIELD')
                ]
            ),
            new BooleanField(
                'IN_SECTIONS',
                [
                    'values' => array('N', 'Y'),
                    'default' => 'N',
                    'title' => Loc::getMessage('ELEMENT_ENTITY_IN_SECTIONS_FIELD')
                ]
            ),
            new StringField(
                'XML_ID',
                [
                    'validation' => [__CLASS__, 'validateXmlId'],
                    'title' => Loc::getMessage('ELEMENT_ENTITY_XML_ID_FIELD')
                ]
            ),
            new StringField(
                'CODE',
                [
                    'validation' => [__CLASS__, 'validateCode'],
                    'title' => Loc::getMessage('ELEMENT_ENTITY_CODE_FIELD')
                ]
            ),
            new StringField(
                'TAGS',
                [
                    'validation' => [__CLASS__, 'validateTags'],
                    'title' => Loc::getMessage('ELEMENT_ENTITY_TAGS_FIELD')
                ]
            ),
            new StringField(
                'TMP_ID',
                [
                    'validation' => [__CLASS__, 'validateTmpId'],
                    'title' => Loc::getMessage('ELEMENT_ENTITY_TMP_ID_FIELD')
                ]
            ),
            new IntegerField(
                'WF_LAST_HISTORY_ID',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_WF_LAST_HISTORY_ID_FIELD')
                ]
            ),
            new IntegerField(
                'SHOW_COUNTER',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_SHOW_COUNTER_FIELD')
                ]
            ),
            new DatetimeField(
                'SHOW_COUNTER_START',
                [
                    'title' => Loc::getMessage('ELEMENT_ENTITY_SHOW_COUNTER_START_FIELD')
                ]
            ),
            new Reference(
                'FILE',
                '\Bitrix\File\File',
                ['=this.DETAIL_PICTURE' => 'ref.ID'],
                ['join_type' => 'LEFT']
            ),
            new Reference(
                'IBLOCK',
                '\Bitrix\Iblock\Iblock',
                ['=this.IBLOCK_ID' => 'ref.ID'],
                ['join_type' => 'LEFT']
            ),
            new Reference(
                'WF_PARENT_ELEMENT',
                '\Bitrix\Iblock\IblockElement',
                ['=this.WF_PARENT_ELEMENT_ID' => 'ref.ID'],
                ['join_type' => 'LEFT']
            ),
            new Reference(
                'IBLOCK_SECTION',
                '\Bitrix\Iblock\IblockSection',
                ['=this.IBLOCK_SECTION_ID' => 'ref.ID'],
                ['join_type' => 'LEFT']
            ),
            new Reference(
                'USER',
                '\Bitrix\User\User',
                ['=this.WF_LOCKED_BY' => 'ref.ID'],
                ['join_type' => 'LEFT']
            ),
        ];
    }

    /**
     * Returns validators for NAME field.
     *
     * @return array
     */
    public static function validateName()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for WF_NEW field.
     *
     * @return array
     */
    public static function validateWfNew()
    {
        return [
            new LengthValidator(null, 1),
        ];
    }

    /**
     * Returns validators for XML_ID field.
     *
     * @return array
     */
    public static function validateXmlId()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for CODE field.
     *
     * @return array
     */
    public static function validateCode()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for TAGS field.
     *
     * @return array
     */
    public static function validateTags()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for TMP_ID field.
     *
     * @return array
     */
    public static function validateTmpId()
    {
        return [
            new LengthValidator(null, 40),
        ];
    }
}
