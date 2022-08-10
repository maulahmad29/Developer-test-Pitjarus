var WebApi = root + '/api/';
var ajaxUrlChart = WebApi + 'dashboard/chart';
var ajaxUrlArea = WebApi + 'dashboard/area';

Chart.pluginService.register({
    beforeRender: function(chart) {
      if (chart.config.options.showAllTooltips) {
        // create an array of tooltips
        // we can't use the chart tooltip because there is only one tooltip per chart
        chart.pluginTooltips = [];
        chart.config.data.datasets.forEach(function(dataset, i) {
          chart.getDatasetMeta(i).data.forEach(function(sector, j) {
            chart.pluginTooltips.push(new Chart.Tooltip({
              _chart: chart.chart,
              _chartInstance: chart,
              _data: chart.data,
              _options: chart.options.tooltips,
              _active: [sector]
            }, chart));
          });
        });
  
        // turn off normal tooltips
        chart.options.tooltips.enabled = false;
      }
    },
    afterDraw: function(chart, easing) {
      if (chart.config.options.showAllTooltips) {
        // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
        if (!chart.allTooltipsOnce) {
          if (easing !== 1)
            return;
          chart.allTooltipsOnce = true;
        }
  
        // turn on tooltips
        chart.options.tooltips.enabled = true;
        Chart.helpers.each(chart.pluginTooltips, function(tooltip) {
          tooltip.initialize();
          tooltip.update();
          // we don't actually need this since we are not animating tooltips
          tooltip.pivot();
          tooltip.transition(easing).draw();
        });
        chart.options.tooltips.enabled = false;
      }
    }
  });
  


function filterChart() {


    $('#dTable').empty();
    var areaList = [];

    $('input[type=checkbox]').each(function () {
        if ($(this).is(':checked')) {
            var areaId = $(this).val();

            areaList.push(areaId);
        }
    })

    if (areaList.length < 1) {
        alert("area belum di pilih");
    }
    else {
        var data = {
            _token: $('input[name=_token]').val(),
            area: areaList,
            startDate: $('input[name=dari_tgl]').val(),
            endDate: $('input[name=sampai_tgl]').val()
        }

        $.ajax({
            type: "POST",
            contentType: "application/json",
            dataType: "json",
            url: ajaxUrlChart,
            data: JSON.stringify(data),
            success: function (resp) {
                if (resp.status == 1) {
                    alert(resp.message);
                }
                else {
                    var total = resp.chart.length;
                    var i = 0;
                    var labelArea = [];
                    var precentage = [];

                    if (total > 0) {
                        for (i; i < total; i++) {
                            labelArea.push(resp.chart[i].area.area_name);
                            precentage.push(resp.chart[i].precentage);
                        }

                        var data = {
                            labels: labelArea,
                            datasets: [
                                {
                                    label: "Area",
                                    data: precentage,
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)'
                                }
                            ]

                        }

                        new Chart($('#barChart').get(0).getContext('2d'), {
                            type: 'bar',
                            data: data,
                            options: {
                                showAllTooltips: true,
                                scales: {
                                    yAxes: [
                                        {
                                            ticks : {
                                                max : 100,    
                                                min : 0
                                              }
                                        }
                                    ]
                                },
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Precentage from 0 to 100'
                                    }
                                }
                             
                            },
                          
                        })

                    }


                    if(resp.brand)
                    {
                        vstrh = '';
                        var a = 0;

                        vstrb = `
                            <thead>
                                <tr>
                                    <th>Brand</th>
                                </tr>
                            </thead>
                        `

                        $('#dTable').append(vstrb);

                        for(a; a < resp.chart.length; a++)
                        {
                            vstrh = `<th>`+ resp.chart[a].area.area_name +`</th>`

                            $('#dTable thead tr').append(vstrh);
                            
                        }

                        $('#dTable').append(`<tbody></tbody>`);

                        for(var d = 0; d < resp.brand.name.length; d++)
                        {
                            var vstrprec = '';
                            for(var x = 0; x < resp.brand.table.length; x++)
                            {
                                vstrprec += `<td>`+ resp.brand.table[x][d].report +` %</td>`;
                            }

                            vstr = `<tr>
                                <td>`+ resp.brand.name[d].brand_name +`</td>
                                `+ vstrprec +`
                            <tr>`;

                            $('#dTable tbody').append(vstr);

                        }
                    }


                }

            }
        })
    }


}

function area() {
    $.ajax({
        type: "GET",
        contentType: "application/json",
        dataType: "json",
        url: ajaxUrlArea,
        success: function (resp) {
            var total = resp.length;
            var i = 0;

            for (i; i < total; i++) {
                var vstr = `<div class="dropdown-item"><div class="form-check">
               <input type="checkbox" class="form-check-input" id="area`+ i + `" value="` + resp[i].area_id + `">
               <label class="form-check-label" for="area`+ i + `">` + resp[i].area_name + `</label>
             </div></div>`;
                $('#selectArea').append(vstr);
            }

        }
    })
}


$(document).ready(function () {
    area();

    $('.flatpickr-basic').flatpickr(
        {
            altInput: true,
            altFormat: 'd/m/Y',
        }
    );
})