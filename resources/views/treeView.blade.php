@extends('app')
@section('body')
<div class="col-12 col-sm-6 col-md-4 mx-auto">
    <nav class="navbar" style="background-color: #e3f2fd;">
        <!-- Navbar content -->

        <div class="d-flex mx-3">
            {{-- Switch Button --}}
    <label class="switch">
        <input type="checkbox" id="switch">
        <span class="slider"></span>
    </label> <h5 class="ml-2"> With Ajax</h5>
</div>

</nav>
<div class="card" >
    <div class="card-body">
    <div id="tree0">

    </div>
    </div>
</div>


</div>
@endsection

@section('script')



    <script>
        var allNodes;
        //this variable use to store all node in first time for show node list without ajax call

        $(document).on('click', 'span.tree-node:not(.expanded)', function() {
            $(this).addClass('expanded');

            if ($('#switch').is(':checked')) {
                var parentId = $(this).parent().attr('data_id');
                console.log('data id', parentId);
                getTree(parentId);
            }
        });
        $(document).on('click', 'span.tree-node.expanded ', function() {
            $(this).removeClass('expanded');
            console.log('remove');

            if ($('#switch').is(':checked')) {
                var parentId = $(this).parent().attr('data_id');
                $('#tree' + parentId + ' .tree-children').remove();

            }
        });
        $(document).on('click', '#switch', function() {
            if ($(this).is(':checked')) {
                console.log(this.allNodes);

                $('#tree0').html('<ul class="tree checktree list"></ul>');
                var $list = $('#tree0 > .list');
                allNodes.forEach(e => {
                    console.log(e.name, e.entry_id);

                    var $li = $(
                        `<li id="tree${e.entry_id}" data_id="${e.entry_id}"><span class="tree-node click_to_ajax ${e.child.length != 0?'tree-btn':''} ">${e.name}</span></li>`
                    );
                    $list.append($li);
                });

            } else {
                setTree(allNodes, 'tree0');

            }
        });


//function for get node list as first time it call for all node list
//  if switch on with ajax this function get single parent data
        function getTree(id = 0) {
            var type = 'all';
            if (id != 0) {
                type = 'single'
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('tree.data') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    id: id
                },
                success: function(response) {
                    //handle response here
                    console.log(response);
                    if (type == 'all') {
                        allNodes = [...response];
                        setTree(response, 'tree0');

                    } else
                        setTree(response, 'tree' + id);

                }
            });
        }

        // this function use for set node to dom element this function run as recursive and one time also
        function setTree(data, branch, type = 'all') {
            console.log(data, branch);


            try {
                if (branch == 'tree0') {
                    $('#' + branch).html('<ul class="tree checktree list"></ul>');
                } else {
                    $('#' + branch).append('<ul class="tree-children list"></ul>');
                }

                var $list = $('#' + branch + ' > .list');

                data.forEach(e => {
                    console.log(e.name, e.entry_id);

                    var $li = $(
                        `<li id="tree${e.entry_id}" data_id="${e.entry_id}"><span class="tree-node ${(e.child&&e.child.length != 0)||e.child_count>0?'tree-btn':''} ">${e.name}</span></li>`
                    );

                    $list.append($li);

                    if (e.child && e.child.length != 0) {
                        setTree(e.child, `tree${e.entry_id}`);
                    }
                });

            } catch (e) {
                console.log(e);
            }

        }

        $(document).ready(function() {

            getTree(); //gettree function call for first time getting all node list with child
        });
    </script>
    @endsection

