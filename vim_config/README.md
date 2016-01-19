===配置文件介绍===
我常用的VIM配置文件，最初是自己收集各种vimrc的配置方法和零散地安装一些plugin，后来用了spf13等项目自动配置后，觉得真的效率高了很多。

===推荐VIM配置的开源项目===
spf13 这个不错，强烈推荐：https://github.com/spf13/spf13-vim   (Linux/MacOSX/Unix等上使用很方便；在Windows上也能用，配置稍麻烦）
另外，在MacOSX上，我也常用maximum-awesome项目：https://github.com/square/maximum-awesome  (仅支持Mac，该配置写ruby很爽，写python也不错）

===VIM源码编译===
在CentOS 5.7 上编译了最新的vim 7.4：
1. 下载源码：
vim官网的tarball下载超级慢或下载失败 http://www.vim.org/
可以选个镜像下载如：https://ftp.heanet.ie/mirrors/ftp.vim.org/pub/vim/unix/
可以用github上的：`git clone git clone https://github.com/vim/vim.git`

2. 配置：src/目录下
./configure --with-features=huge --enable-pythoninterp --with-python-config-dir=/usr/local/python/lib
注1: --with-features=huge 编译尽可能多的features到VIM
注2: 将python的支持配置好，避免“error: Pymode requires vim compiled with +python.”
注3: 可能会生成 auto/config.cache 文件，必要时可以先删除它再重新configure

3. 编译：src/目录下
make -j8 && sudo make install
注：默认安装在/usr/local/bin/vim  确保/usr/local/bin在$PATH中。
