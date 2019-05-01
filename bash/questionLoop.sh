# loop until question answer is = to string in array
# 
function askQuestionLoop(){
    # required 
    if [ -z $2 ]; then
        echo ;
        echo '---- arguments missing ---';
        echo 'askQuestionLoop <<question>> <<name of answers array>>';
        echo ;
        echo 'example script:';
        echo ;
        echo "# array of allowed answers";
        echo "allowed=('y' 'n')";
        echo "# run script";
        echo 'askQuestionLoop "my question" allowed ';
        echo '# use answer';
	echo 'echo "returned = $returned";'
        return;
    fi;
    #
    local i;     # make not usable outside function
    local varName;     # make not usable outside function
    local question=${1};
    #
    local name=$2[@]; # name of the array
    local allowedOptions=("${!name}"); # the array content
    #
    local loop=true;
    while [ "$loop" == true ]; do 
      echo question;
      read varName;
      for i in "${allowedOptions[@]}"; do
          if [ "$i" == "$varName" ] ; then
              returned="$varName";
              loop=false;
          fi
      done
    done
}

