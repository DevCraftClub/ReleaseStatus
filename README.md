![version](https://img.shields.io/badge/version-1.0-red.svg?style=flat-square "Version")
![DLE](https://img.shields.io/badge/DLE-9.X--11.x-green.svg?style=flat-square "DLE Version")
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/Gokujo/ReleaseStatus/master/LICENSE)
[![Инструкция](https://img.shields.io/badge/manual-Инструкция-orange.svg?style=flat-square)](http://help.maxim-harder.de/topic/18-releasestatus-10-ustanovka/)

![ReleaseStatus - дай знать о статусе релиза другим!](/logo.png)

# ReleaseStatus
Статус релизов v1.0

<b>Возможности</b><br>
- Вывод 4ёх статусов: перевод, озвучка, монтаж и проверка
- В админке можно заменить эти значения на свои
- Вывод стаусов в процентах или словах
- Настраивается полностью в админпанеле
- Подключается одной строкой
<br><br>
<b>Теги для release_block.tpl</b><br><br>

- {image}, {image-1}, {image-*} - При условии, что изображения выводятся из короткой или полной новости
- {poster} - При условии, если изображение выводится из доп. поля
- {title} - Выводит название в зависимости от вывода, настроенного в настройках
- {type} - Выводит тип релиза, полнометражку или сериал
- {number} - Выводит номер серии, если релиз полнометражка - не выводится
- {translate_name} - Выводит название поля: "Перевод"
- {dub_name} - Выводит название поля: "Озвучка"
- {montage_name} - Выводит название поля: "Монтаж"
- {post_name} - Выводит название поля: "Проверка"
- {translate} - Выводит значение для поля: "Перевод"
- {dub} - Выводит значение для поля: "Озвучка"
- {montage} - Выводит значение для поля: "Монтаж"
- {post} - Выводит значение для поля: "Проверка"
- {suffix} - Выводит знак процента
- {progress} - Выводит прогрессбар, статус в процентах. Если отключены показы в процентах, то и прогрессбара не будет
- {link} - Ссылка на новость
- {id} - ID статуса
- [status][/status] - Скрывает текст, если отключён показ нуллевых значений (пока в стадии обдумки)
- [link][/link] - Заключённый текст превратится в ссылку на новость
